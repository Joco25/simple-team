<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConversationComment extends Model
{
    public function user()
	{
		return $this->belongs_to('User');
	}

	public function is_liked($user_id)
	{
		$count = Like::where_comment_id($this->id)
			->where_deleted(0)
			->where_user_id($user_id)
			->count();

		return $count > 0;
	}

    public function like_count()
    {
        return Like::where_comment_id($this->id)
            ->where_deleted(0)
            ->count();
    }

    public function like_sum()
    {
        return Like::where_comment_id($this->id)
            ->where_deleted(0)
            ->sum('value');
    }

	public function like($user_id, $value = 1)
	{
		return Like::create(array(
			'comment_id' => $this->id,
			'user_id' => $user_id,
            'value' => $value
		));
	}

    public function unlike($user_id)
	{
		return Like::where_comment_id($this->id)
            ->where_deleted(0)
			->where_user_id($user_id)
            ->update([
                'deleted' => 1
            ]);
	}

	public function likes()
	{
		return $this->has_many('Like')
            ->where_deleted(0);
	}

	public function users()
	{
		return $this->has_many_and_belongs_to('User');
	}
}
