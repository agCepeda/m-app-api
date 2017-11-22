<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\Commands\SendNotification;
use App\Notifications\Publisher;

class CommandServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.notification.send', function($app)
        {
    		$config = config('push_notification');
    		
    		$publisher = new Publisher($config);
            return new SendNotification($publisher);
        });

        $this->commands(
            'command.notification.send'
        );
    }
}