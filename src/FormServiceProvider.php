<?php

namespace LiveCMS\Form;

use Collective\Html\FormFacade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use LiveCMS\Transport\HtmlTransport;
use LiveCMS\Transport\JavascriptTransport;

class FormServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/form.php' => config_path('form.php'),
        ], 'config');

        FormFacade::macro('create', function ($form = null) {
            $name = Route::currentRouteName();
            return app(HtmlTransport::class)->set($form, $name);
        });
        FormFacade::macro('render', function ($name = null) {
            $name = $name ?? Route::currentRouteName();
            return app(HtmlTransport::class)->get($name);
        });
        FormFacade::macro('javascript', function () {
            return app(JavascriptTransport::class)->get();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/form.php', 'form');
    }
}
