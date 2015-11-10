<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiTopicController extends Controller
{
	public function get_latest()
	{
		$s = Api::inputs([
			'serie_id' => 0,
			'page' => 1,
			'take' => 50
		]);

		$account = account();
		$serie_ids = Account::all_serie_ids($account->id);
		$serie_ids = count($serie_ids) > 0 ? $serie_ids : [0];
		$t = implode($serie_ids, ',');

		// all: team and serie
		// 0: team
		// >0: serie
		if($s['serie_id'] == 'all') {
			$serie_query = "(`serie_id` IN ({$t}) OR `serie_id`=0 AND `account_user_id`=" . $account->id . ")";
		} else if ($s['serie_id'] > 0) {
			$serie_query = "`serie_id` IN ({$t})";
		} else if ($s['serie_id'] == 0) {
			$serie_query = "`serie_id`=0 AND `account_user_id`=" . $account->id;
		}

		$d['topics'] = Topic::with('serie')
			->where_deleted(0)
			->raw_where($serie_query)
			->for_page($s['page'], $s['take'])
			->order_by('updated_at', 'desc');

		if ($s['serie_id'] > 0) {
			$d['topics']->where_serie_id($s['serie_id']);
		}

		$d['topics'] = $d['topics']->get();

		_::each($d['topics'], function($topic) use ($account) {
			$topic->is_starred = $topic->is_starred(Auth::user()->id);
			$topic->users = $topic->users();
			$topic->is_unread = $topic->is_unread();
			$topic->created_at = from_utc($topic->created_at, $account->timezone);
			$topic->updated_at = from_utc($topic->updated_at, $account->timezone);
		});

		return Api::json($d);
	}

	public function get_starred()
	{
		$s = Api::inputs([
			'serie_id' => 0,
			'page' => 1,
			'take' => 50
		]);

		$account = account();
		$serie_ids = Account::all_serie_ids($account->id);
		$serie_ids = count($serie_ids) > 0 ? $serie_ids : [0];
		$t = implode($serie_ids, ',');

		// all: team and serie
		// 0: team
		// >0: serie
		if($s['serie_id'] == 'all') {
			$serie_query = "(`topics`.`serie_id` IN ({$t}) OR `topics`.`serie_id`=0 AND `topics`.`account_user_id`=" . $account->id . ")";
		} else if ($s['serie_id'] > 0) {
			$serie_query = "`topics`.`serie_id` IN ({$t})";
		} else if ($s['serie_id'] == 0) {
			$serie_query = "`topics`.`serie_id`=0 AND `topics`.`account_user_id`=" . $account->id;
		}

		$d['topics'] = Topic::with('serie')
			->where('topics.deleted', '=', 0)
			->join('topic_user_stars', 'topic_user_stars.topic_id', '=', 'topics.id')
			->raw_where($serie_query)
			->where('topic_user_stars.user_id', '=', Auth::user()->id);

		if ($s['serie_id'] > 0) {
			$d['topics']->where_serie_id($s['serie_id']);
		}

		$d['topics'] = $d['topics']->order_by('topic_user_stars.id', 'desc')
			->for_page($s['page'], $s['take'])
			->get(['topics.id', 'topics.name', 'topics.serie_id', 'topics.updated_at', 'topics.created_at']);

		_::each($d['topics'], function($topic) use ($account) {
			$topic->is_starred = $topic->is_starred(Auth::user()->id);
			$topic->users = $topic->users();
			$topic->is_unread = $topic->is_unread();
			$topic->created_at = from_utc($topic->created_at, $account->timezone);
			$topic->updated_at = from_utc($topic->updated_at, $account->timezone);
		});

		return Api::json($d);
	}

	public function get_unread()
	{
		$s = Api::inputs([
			'serie_id' => 0,
			'page' => 1,
			'take' => 50
		]);

		$offset = ($s['page'] - 1) * $s['take'];

		$account = account();
		$serie_ids = Account::all_serie_ids($account->id);
		$serie_ids = count($serie_ids) > 0 ? $serie_ids : [0];
		$t = implode($serie_ids, ',');

		// all: team and serie
		// 0: team
		// >0: serie
		if($s['serie_id'] == 'all') {
			$serie_query = "(`serie_id` IN ({$t}) OR `serie_id`=0 AND `account_user_id`=" . $account->id . ")";
		} else if ($s['serie_id'] > 0) {
			$serie_query = "`serie_id` IN ({$t})";
		} else if ($s['serie_id'] == 0) {
			$serie_query = "`serie_id`=0 AND `account_user_id`=" . $account->id;
		}

		// http://stackoverflow.com/questions/23461042/selecting-the-last-record-and-comparing-a-datetime?noredirect=1#comment35967018_23461042
		$topics = DB::query("
			SELECT t.id as id
			FROM topics t
			WHERE not exists(
				SELECT tuv.topic_id, max(tuv.created_at) as last_view, max(tp.created_at) as last_post
				from topic_user_views tuv
				inner join topic_posts as tp
				on tuv.topic_id=tp.topic_id and tuv.created_at > tp.created_at
				group by topic_id
				having t.id=topic_id)
			AND {$serie_query}
			ORDER BY `id` DESC
			LIMIT {$offset}, " . (int) $s['take']
		);

		$topic_ids = _::pluck($topics, 'id');
		$topic_ids = count($topic_ids) > 0 ? $topic_ids : [0];

		$d['topics'] = Topic::with('serie')
			->where_in('id', $topic_ids)
			->where_deleted(0)
			->get();

		_::each($d['topics'], function($topic) use ($account) {
			$topic->is_starred = $topic->is_starred(Auth::user()->id);
			$topic->users = $topic->users();
			$topic->is_unread = $topic->is_unread();
			$topic->created_at = from_utc($topic->created_at, $account->timezone);
			$topic->updated_at = from_utc($topic->updated_at, $account->timezone);
		});

		return Api::json($d);
	}

	public function get_top()
	{
		$s = Api::inputs([
			'serie_id' => 0,
			'page' => 1,
			'take' => 50
		]);

		$offset = ($s['page'] - 1) * $s['take'];
		$account = account();
		$serie_ids = Account::all_serie_ids($account->id);
		$serie_ids = count($serie_ids) > 0 ? $serie_ids : [0];
		$t = implode($serie_ids, ',');

		$post_weight = 300;
		$like_weight = 1000000;
		$view_weight = 5;
		$created_at_weight = .4;
		$updated_at_weight = 3;

		// all: team and serie
		// 0: team
		// >0: serie
		if($s['serie_id'] == 'all') {
			$serie_query = "(`topics`.`serie_id` IN ({$t}) OR `topics`.`serie_id`=0 AND `topics`.`account_user_id`=" . $account->id . ")";
		} else if ($s['serie_id'] > 0) {
			$serie_query = "`topics`.`serie_id` IN ({$t})";
		} else if ($s['serie_id'] == 0) {
			$serie_query = "`topics`.`serie_id`=0 AND `topics`.`account_user_id`=" . $account->id;
		}

		$d['topics'] = Topic::with('serie')
			->select([
				'id',
				'name',
				'serie_id',
				'like_count',
				'post_count',
				'view_count',
				'created_at',
				'updated_at',
				DB::raw("((post_count * {$post_weight}) + (like_count * {$like_weight}) + (view_count * {$view_weight}) + (UNIX_TIMESTAMP(created_at) * {$created_at_weight}) + (UNIX_TIMESTAMP(updated_at) * {$updated_at_weight})) as popularity_score")
			])
			->where('deleted', '=', 0)
			->raw_where($serie_query)
			->for_page($s['page'], $s['take'])
			->order_by('popularity_score', 'desc')
			->get(['name']);

		_::each($d['topics'], function($topic) use ($account) {
			$topic->is_starred = $topic->is_starred(Auth::user()->id);
			$topic->users = $topic->users();
			$topic->is_unread = $topic->is_unread();
			$topic->created_at = from_utc($topic->created_at, $account->timezone);
			$topic->updated_at = from_utc($topic->updated_at, $account->timezone);
		});

		return Api::json($d);
	}

	public function post_create()
	{
		$s = Api::inputs([
			'name' => '',
			'serie_id' => 0,
			'body' => '',
			'user_ids' => []
		]);

		$topic = Topic::create([
			'user_id' => Auth::user()->id,
			'account_user_id' => Auth::user()->account_user_id,
			'serie_id' => $s['serie_id'],
			'name' => $s['name'],
			'post_count' => 1
		]);

		$post = TopicPost::create([
			'user_id' => Auth::user()->id,
			'account_user_id' => Auth::user()->account_user_id,
			'body' => $s['body'],
			'topic_id' => $topic->id
		]);

		TopicUserNotification::create([
			'user_id' => Auth::user()->id,
			'topic_id' => $topic->id,
			'type' => 'watching'
		]);

		_::each($s['user_ids'], function($user_id) use ($topic) {
			$topic->add_notifications($user_id);
		});

		$topic->send_notifications($post);

		return Api::json(compact('topic'));
	}

	public function delete_delete($id = 0)
	{
		$topic = Topic::find($id);
		has_access($topic);

		return Api::success($topic->soft_delete());
	}

	public function get_read($id = 0)
	{
		$account = account();
		$topic = Topic::with(['posts', 'posts.user', 'serie', 'posts.posts', 'posts.posts.user'])
			->find($id);

		if (! $topic) {
			return Api::error("Could not find topic.");
		}

		$topic->is_starred = $topic->is_starred(Auth::user()->id);
		$topic->created_at = from_utc($topic->created_at, $account->timezone);
		$topic->updated_at = from_utc($topic->updated_at, $account->timezone);

		_::each($topic->posts, function($post) {
			$post->is_liked = $post->is_liked(Auth::user()->id);
		});

		return Api::json(compact('topic'));
	}

	public function post_star($id = 0)
	{
		$topic = Topic::find($id);
		if (! $topic) {
			return Api::error("Can't find topic.");
		}

		$topic->create_star(Auth::user()->id);
	}

	public function delete_star($id = 0)
	{
		$topic = Topic::find($id);
		if (! $topic) {
			return Api::error("Can't find topic.");
		}

		$topic->delete_star(Auth::user()->id);
	}

	public function post_view($id = 0)
	{
		$topic = Topic::find($id);
		if (! $topic) {
			return Api::error("Can't find topic.");
		}

		$cookie_name = 'topic_user_view_' . $id . '_' . Auth::user()->id;

		TopicUserView::create([
			'user_id' => Auth::user()->id,
			'account_user_id' => Auth::user()->account_user_id,
			'topic_id' => $id
		]);

		$topic->update_view_count();

		return Api::success();
	}

	public function get_user_notification($topic_id, $user_id)
	{
		$d['notification'] = TopicUserNotification::where_user_id(Auth::user()->id)
			->where_topic_id($topic_id)
			->first();

		return Api::json($d);
	}

	public function delete_user_notification($topic_id, $user_id)
	{
		$topic = Topic::find($topic_id);
		$success = $topic->remove_notifications(Auth::user()->id);

		return Api::success($success);
	}

	public function post_user_notification($topic_id, $user_id)
	{
		$topic = Topic::find($topic_id);
		$d['notification'] = $topic->add_notifications(Auth::user()->id);

		return Api::json($d);
	}
}
