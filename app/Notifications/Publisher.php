<?php

namespace App\Notifications;

use App\Session;
use App\User;
use Log;

use sngrl\PhpFirebaseCloudMessaging\Client;
use sngrl\PhpFirebaseCloudMessaging\Message;
use sngrl\PhpFirebaseCloudMessaging\Recipient\Device;
use sngrl\PhpFirebaseCloudMessaging\Notification;

class Publisher 
{
	private $config;
	
	public function __construct(Client $client)
	{
		$this->client = $client;
		$this->client->setApiKey(env('FIREBASE_API_KEY', null));
	}


	public function sendFollowerNotificationToUser($userId) 
	{	
		$user = app()[User::class];

		$message  = "{$user->name} {$user->last_name} started following you.";

		$noNotifications = $this->getNotificationCount($userId);

		$payload  = [
			"aps" => [
				"alert"    => $message,
				"sound"    => "default",
				"link_url" => "https://youtube.com",
				"badge"    => $noNotifications,
				"user_id"  => $user->id
			]
		];

		$this->sendNotificationToUserDevices($userId, $payload);
	}


	public function sendReviewNotificationToUser($userId) 
	{
		$user = app()[User::class];

		$message  = "{$user->name} {$user->last_name} posted a review in your profile.";

		$noNotifications = $this->getNotificationCount($userId);

		$payload  = [
			"aps" => [
				"alert"    => $message,
				"sound"    => "default",
				"link_url" => "https://youtube.com",
				"badge"    => $noNotifications,
				"user_id"  => $user->id
			]
		];

		$this->sendNotificationToUserDevices($userId, $payload);
	}

	private function sendNotificationToUserDevices($userId, $payload)
	{
		$deviceTokens = $this->getDeviceTokensOfUser($userId);

		if (count($deviceTokens) > 0) {
			$message = new Message();
			$message->setPriority('high');

			foreach ($deviceTokens as $deviceToken) {
				$message->addRecipient(new Device($deviceToken));
			}

			$notification = new Notification('Meisshi', $payload['aps']['alert']);
			$notification->setBadge($payload['aps']['badge']);

			$message
				->setNotification($notification)
				->setData($payload['aps']);

			$this->client->send($message);
		}
	}

	private function getDeviceTokensOfUser($userId)
	{
		$deviceTokens = Session::where('user_id', $userId)
								->whereNotNull('device_token')
								->get(['device_token'])
								->pluck('device_token');


		app('log')->debug("User device tokens:");
		app('log')->debug(print_r($deviceTokens, true));

		return $deviceTokens;
	}


	private function getNotificationCount($userId) 
	{
		return app('db')
				->table('notifications AS n')
				->where('user_id', $userId)
				->where('seen', false)
				->groupBy('user_id')
				->first([
					app('db')->raw('ifnull(count(*), 0) AS noNotifications')
				])
				->noNotifications;
	}
}