<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Notifications\Publisher;

use sngrl\PhpFirebaseCloudMessaging\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Publisher::class, function ($app) {
            $client = new Client();
            $client->setApiKey(env('FIREBASE_API_KEY', null));

            return new Publisher($client);
        });
    }
}
