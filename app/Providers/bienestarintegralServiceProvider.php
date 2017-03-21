<?php

namespace App\Providers;

use App\Bienestarintegral\Messages\OperationMessage;
use Illuminate\Support\ServiceProvider;

class bienestarintegralServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(OperationMessage::class, function(){
            return new OperationMessage();
        });
    }
}
