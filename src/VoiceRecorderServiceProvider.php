<?php

namespace Zareismail\Fields;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider; 
use Laravel\Nova\Nova;

class VoiceRecorderServiceProvider extends ServiceProvider implements DeferrableProvider
{ 
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Nova::script('zareismail-voice-recorder', __DIR__.'/../dist/js/field.js');
        Nova::style('zareismail-voice-recorder', __DIR__.'/../dist/css/field.css');
    }
    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     * Get the events that trigger this service provider to register.
     *
     * @return array
     */
    public function when()
    {
        return [
            \Laravel\Nova\Events\ServingNova::class,
        ];
    }
}
