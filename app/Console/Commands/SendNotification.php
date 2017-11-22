<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Notifications\Publisher;

class SendNotification extends Command
{

	protected $signature = 'notification:send {user} {follower}';

	protected $description = 'Send notification to user';

	public function __construct(Publisher $publisher)
	{
        parent::__construct();
		$this->publisher = $publisher;
	}

	public function handle()
	{
		$user     = $this->argument('user');
		$follower = $this->argument('follower');

		$this->publisher->sendReviewNotificationToUser($user, $follower);
	}

}