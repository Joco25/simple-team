<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
	public function posts()
	{
		return $this->has_many('TopicPost');
	}

	public function post_count()
	{
		return $this->has_many('TopicPost')
			->where_deleted(0)
			->count();
	}

	public function serie()
	{
		return $this->belongs_to('Serie');
	}

	public function user()
	{
		return $this->belongs_to('User');
	}

	public function is_starred($user_id)
	{
		$count = DB::table('topic_user_stars')
			->where_user_id($user_id)
			->where_topic_id($this->id)
			->count();

		return $count > 0;
	}

	public function star_count()
	{
		return TopicUserStar::where_topic_id($this->id)
			->count();
	}

	public function create_star($user_id)
	{
		return TopicUserStar::create([
			'user_id' => $user_id,
			'topic_id' => $this->id
		]);
	}

	public function delete_star($user_id)
	{
		return TopicUserStar::where_user_id($user_id)
			->where_topic_id($this->id)
			->delete();
	}

	public function view_count()
	{
		return DB::table('topic_user_views')
			->where_topic_id($this->id)
			->count();
	}

	public function like_count()
	{
		return Topic::where('topics.id', '=', $this->id)
			->where('topics.deleted', '=', 0)
			->join('topic_posts', 'topic_posts.topic_id', '=', 'topics.id')
			->where('topic_posts.deleted', '=', 0)
			->join('topic_post_user_likes', 'topic_post_user_likes.topic_post_id', '=', 'topic_posts.id')
			->count();
	}

	public function users()
	{
		return Topic::where('topics.id', '=', $this->id)
			->where('topics.deleted', '=', 0)
			->join('topic_posts', 'topic_posts.topic_id', '=', 'topics.id')
			->where('topic_posts.deleted', '=', 0)
			->join('users', 'users.id', '=', 'topic_posts.user_id')
			->where('users.deleted', '=', 0)
			->distinct()
			->get(['users.name', 'users.image']);
	}

	public function update_post_count()
	{
		DB::table('topics')
			->where_id($this->id)
			->update([
				'post_count' => $this->post_count()
			]);
	}

	public function update_like_count()
	{
		DB::table('topics')
			->where_id($this->id)
			->update([
				'like_count' => $this->like_count()
			]);
	}

	public function update_view_count()
	{
		DB::table('topics')
			->where_id($this->id)
			->update([
				'view_count' => $this->view_count()
			]);
	}

	public function is_unread()
	{
		$query = DB::query("
			SELECT COUNT(*) as topic_count
			FROM topics t
			WHERE NOT exists(
				SELECT tuv.topic_id, max(tuv.created_at) as last_view, max(tp.created_at) as last_post
				from topic_user_views tuv
				inner join topic_posts as tp
				on tuv.topic_id=tp.topic_id and tuv.created_at > tp.created_at
				group by topic_id
				having t.id=topic_id)
				AND t.id = {$this->id}
		");

		return $query[0]->topic_count > 0;
	}

	public function notifications()
	{
		return $this->has_many('TopicUserNotification');
	}

	public function send_notifications($post)
	{
		$body = "
		<table>
			<tr>
				<td>
					<img src='" . url('/image?image=' . $post->user->image . '&size=50') . "'>
				</td>
				<td>
					<strong>{$post->user->name}</strong>
					{$post->body}
					<p>
						<a href='" . url('profile#/profile/social/topics/' . $this->id) . "'>
							Continue Reading
						</a>
					</p>
				</td>
			</tr>
		</table>
		";

		foreach ($this->notifications as $notification) {
			if ($notification->user_id == Auth::user()->id || !$notification->user) {
				continue;
			}

			$email = Email::create([
				'to' => $notification->user->email,
				'subject' => "New Post In {$this->name}",
				'body' => render("templates.email", [
					'header' => "New Post In {$this->name}",
					'content' => $body
				])
			]);

			Queue::create([
				'email_id' => $email->id,
				'type' => 'send_email',
			]);
		}
	}

	public function remove_notifications($user_id)
	{
		return TopicUserNotification::where_user_id($user_id)
			->where_topic_id($this->id)
			->delete();
	}

	public function add_notifications($user_id)
	{
		$this->remove_notifications($user_id);

		return DB::table('topic_user_notifications')
			->insert([
				'topic_id' => $this->id,
				'user_id' => $user_id,
				'type' => 'watching'
			]);
	}
}
