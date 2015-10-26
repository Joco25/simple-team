<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    public function comments()
	{
		return $this->has_many('Comment')
			->order_by('id')
			->where_deleted(0);
	}

	public function comments_count()
	{
		return $this->has_many('Comment')
			->where_deleted(0)
			->count();
	}

	public function recent_comments()
	{
		return $this->has_many('Comment')
			->order_by('id', 'desc')
			->where_deleted(0);
	}

    public function last_comment()
	{
		return $this->has_many('Comment')
			->where_deleted(0)
            ->order_by('id', 'desc')
            ->first();
	}

	public function category()
	{
		return $this->belongs_to('Category')
			->where_deleted(0);
	}

	public function comment_user_ids()
	{
		return Comment::where_conversation_id($this->id)
			->where_deleted(0)
			->lists('user_id');
	}

	public function comment_users()
	{
		return User::where_in('id', $this->comment_user_ids())
			->where_deleted(0)
			->get();
	}

	public function serie()
	{
		return $this->belongs_to('Serie');
	}

	public function users()
	{
		return $this->has_many_and_belongs_to('User');
	}

	public function user_ids()
	{
		return DB::table('conversation_user')
			->where_conversation_id($this->id)
			->lists('user_id');
	}

	public function attached_user_ids()
	{
		$comment_ids = DB::table('comments')
			->where_conversation_id($this->id)
			->where_deleted(0)
			->lists('id');

		return DB::table('comment_user')
			->where_in('comment_id', $comment_ids)
			->lists('user_id');
	}
}
