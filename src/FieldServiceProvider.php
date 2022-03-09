<?php

namespace Jezzdk\NovaKontainer;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class FieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-kontainer', __DIR__.'/../dist/js/field.js');
            Nova::style('nova-kontainer', __DIR__.'/../dist/css/field.css');

            Nova::translations([
                'Kontainer URL is missing' => 'Kontainer URL is missing',
                'Error parsing image data' => 'Error parsing image data',
                'Invalid URL' => 'Invalid URL',
                'Unknown type' => 'Unknown type',
                'Only images allowed' => 'Only images allowed',
                'Only videos allowed' => 'Only videos allowed',
                'Only files allowed' => 'Only files allowed',
                'Edit' => 'Edit',
                'Browse' => 'Browse',
                'Unlink' => 'Unlink',
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
