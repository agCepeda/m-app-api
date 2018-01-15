<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Notification;

class NotificationController extends Controller
{
	const NO_ITERATIONS = 30;
	const DELAY = 1;

	public function __construct()
	{
		$this->middleware('auth.user');
	}

	public function index(User $user, Request $request)
	{
		$page = $request->get('page', 1);
		$size = $request->get('size', 20);
		$notifications = $user->notifications()
							  ->with('attachment')
							  //->where('seen', false)
							  ->paginate($size, ['*'], 'page', $page);

		app('db')
			->table('notifications')
			->where('user_id', $user->id)
			->update([
				'seen' => true
			]);

		return response()->json($notifications);
	}

	public function update($notificationId)
	{
		$notification = Notification::find($notificationId);
		$notification->seen = true;
		$notification->save();
	}

	public function notificationPolling(
		User $user,
		Request $request
	) {
		$db = app('db');

		$notificationToken = $request->get('token', null);
		$token = null;

		$queryBuilder = $db->table('users')
					 ->where('id', $user->id)
					 ->select(['notification_token']);
		$count = 0;

		do {
			sleep(self::DELAY);
			$token = $queryBuilder->first()->notification_token;
			$count ++;
		} while ($notificationToken == $token && $count <= self::NO_ITERATIONS);

		return response()->json([
				'token'         => $token,
				'notifications' => $user->notifications()->with('attachment')->get()
			]);
	}
}