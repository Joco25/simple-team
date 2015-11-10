<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiTopicPostController extends Controller
{
	public function store($topic_id = 0)
	{
		$s = Api::inputs([
			'body',
			'post_id',
			'user_ids' => []
		]);

		$topic = Topic::find($topic_id);
		if (! $topic) {
			return Api::error("Could not find topic.");
		}

		$topic->touch();

		$post = TopicPost::create([
			'user_id' => Auth::user()->id,
			'account_user_id' => Auth::user()->account_user_id,
			'body' => $s['body'],
			'topic_id' => $topic_id,
			'topicpost_id' => $s['post_id']
		]);

		_::each($s['user_ids'], function($user_id) use ($topic) {
			$topic->add_notifications($user_id);
		});

		$post->user;
		$post->topic->update_post_count();
		$post->topic->send_notifications($post);

		return Api::json(compact('post'));
	}

	public function put_update($id = 0)
	{
		$s = Api::inputs([
			'body' => ''
		]);

		$post = TopicPost::find($id);
		has_access($post);

		$post->body = $s['body'];

		return Api::success($post->save());
	}

	public function delete_delete($id = 0)
	{
		$post = TopicPost::find($id);
		has_access($post);

		$post->delete();
		$post->topic->update_post_count();

		return Api::success();
	}

	public function post_like($id = 0)
	{
		$post = TopicPost::find($id);
		if (! $post) {
			return Api::error("Can't find post.");
		}

		$post->create_like(Auth::user()->id);
		$post->topic->update_like_count();

		return Api::success();
	}

	public function delete_like($id = 0)
	{
		$post = TopicPost::find($id);
		if (! $post) {
			return Api::error("Can't find post.");
		}

		$post->delete_like(Auth::user()->id);
		$post->topic->update_like_count();

		return Api::success();
	}

}
