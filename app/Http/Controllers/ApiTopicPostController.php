<?php

namespace App\Http\Controllers;

use Input;
use Auth;
use App\Topic;
use App\TopicPost;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ApiTopicPostController extends Controller
{
	public function store()
	{
		$inputs = Input::all();

		$topic = Topic::whereId($inputs['topic_id'])
			->whereTeamId(Auth::user()->team_id)
			->first();

		if (! $topic) abort("Could not find topic.");

		$topic->touch();

		$post = TopicPost::create([
			'user_id' => Auth::user()->id,
			'team_id' => Auth::user()->team_id,
			'body' => $inputs['body'],
			'topic_id' => $topic->id,
			'topic_post_id' => Input::get('topic_post_id', 0)
		]);

		// _::each($s['user_ids'], function($user_id) use ($topic) {
		// 	$topic->add_notifications($user_id);
		// });

		$post->user;
		$post->topic->updatePostCount();
		// $post->topic->send_notifications($post);

		return response()->json([
			'post' => $post
		]);
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
