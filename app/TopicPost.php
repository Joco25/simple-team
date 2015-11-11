<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopicPost extends Model
{
    public static $table = 'topic_posts';

	public function topic()
	{
		return $this->belongs_to('Topic');
	}

	public function posts()
	{
		return $this->has_many('TopicPost');
	}

	public function user()
	{
		return $this->belongs_to('User');
	}

	public function is_liked($user_id)
	{
		$count = TopicPostUserLike::where_user_id($user_id)
			->where_topic_post_id($this->id)
			->count();

		return $count > 0;
	}

	public function create_like($user_id)
	{
		return TopicPostUserLike::create([
			'user_id' => $user_id,
			'topic_post_id' => $this->id
		]);
	}

	public function delete_like($user_id)
	{
		return TopicPostUserLike::where_user_id($user_id)
			->where_topic_post_id($this->id)
			->delete();
	}
}
