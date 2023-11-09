<?php

namespace Simotel\Laravel;

use Simotel\Simotel;
use Illuminate\Support\ServiceProvider;

class SimotelLaravelServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * Configurations that needs to be done by user.
         */
        $this->publishes(
            [
                Simotel::getDefaultConfigPath() => config_path('simotel-laravel.php'),
            ],
            'config'
        );


    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Bind to service container.
         */
        $this->app->bind('simotel-laravel-connect', function () {
            $config = config('simotel-laravel') ?? [];
            return new Simotel($config);
        });

        $this->registerEvents();
    }

    /**
     * Register Laravel events.
     *
     * @return void
     */
    public function registerEvents()
    {

        $events = [
            "Cdr", "NewState", "IncomingCall", "OutgoingCall", "Transfer", "ExtenAdded", "ExtenRemoved",
            "IncomingFax", "IncomingFax", "CdrQueue", "VoiceMail", "VoiceMailEmail", "Survey", "Ping"
        ];


        foreach ($events as $event)
            \Simotel\Laravel\Facade\Simotel::eventApi()->addListener($event, function ($data) use ($event) {
                $eventClassName = "Simotel\Laravel\Events\SimotelEvent" . $event;
                event(new $eventClassName(request()->all()));
            });

    }

}
