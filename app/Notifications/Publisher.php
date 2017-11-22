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


	public function sendFollowerNotificationToUser($userId, $followerId) 
	{
		app('log')->debug("Sending follower notification to {$followerId}");
		
		$user = app()[User::class];

		$message  = $user->name . ' ' . $user->last_name . ' started following you.';

		$payload  = [
			"aps" => [
				"alert"    => $message,
				"sound"    => "default",
				"link_url" => "https://youtube.com",
				// "badge"    => $follower->notifications_count,
				"user_id"  => $user->id
			]
		];
		Log::info("Send follower notification by userId: {$followerId}");

		$this->sendNotificationToUserDevices($followerId, $payload);
	}


	public function sendReviewNotificationToUser($userId, $followerId) 
	{
		app('log')->debug("Sending review notification to {$followerId}");
		
		$user = app()[User::class];

		$message  = $user->name . ' ' . $user->last_name . ' posted a review in your profile.';

		$payload  = [
			"aps" => [
				"alert"    => $message,
				"sound"    => "default",
				"link_url" => "https://youtube.com",
				// "badge"    => $follower->notifications_count,
				"user_id"  => $followerId
			]
		];

		Log::info("Send review notification userId: {$userId} followerId: {$followerId}");

		$this->sendNotificationToUserDevices($followerId, $payload);
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

			$message
				->setNotification(
					new Notification('Meisshi', $payload['aps']['alert']))
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


	private function publishNotificationToDevice($deviceToken, array $payload) 
	{

	}
}