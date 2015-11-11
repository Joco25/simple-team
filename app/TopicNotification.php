<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TopicNotification extends Model
{
    public static $table = 'topic_notifications';

	public function user()
	{
		return $this->belongs_to('User');
	}
}
