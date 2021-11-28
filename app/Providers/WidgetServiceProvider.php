<?php

namespace App\Providers;

use Arrilot\Widgets\Console\WidgetMakeCommand;
use Arrilot\Widgets\Factories\AsyncWidgetFactory;
use Arrilot\Widgets\Factories\WidgetFactory;
use Arrilot\Widgets\Misc\LaravelApplicationWrapper;
use Illuminate\Support\Facades\Blade;

class WidgetServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('arrilot.widget', function () {
            return new WidgetFactory(new LaravelApplicationWrapper());
        });

        $this->app->bind('arrilot.async-widget', function () {
            return new AsyncWidgetFactory(new LaravelApplicationWrapper());
        });

        $this->app->singleton('arrilot.widget-group-collection', function () {
            return new WidgetGroupCollection(new LaravelApplicationWrapper());
        });

        $this->app->singleton('arrilot.widget-namespaces', function () {
            return new NamespacesRepository();
        });

        $this->app->singleton('command.widget.make', function ($app) {
            return new WidgetMakeCommand($app['files']);
        });

        $this->commands('command.widget.make');

        $this->app->alias('arrilot.widget', 'Arrilot\Widgets\Factories\WidgetFactory');
        $this->app->alias('arrilot.async-widget', 'Arrilot\Widgets\Factories\AsyncWidgetFactory');
        $this->app->alias('arrilot.widget-group-collection', 'Arrilot\Widgets\WidgetGroupCollection');
    }

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $routeConfig = [
            'namespace'  => 'Arrilot\Widgets\Controllers',
            'prefix'     => 'arrilot',
            'middleware' => $this->app['config']->get('laravel-widgets.route_middleware', []),
        ];

        $this->app['router']->group($routeConfig, function ($router) {
            $router->get('load-widget', 'WidgetController@showWidget');
        });
    }
}
