<?php

namespace AwemaPL\Xml;

use AwemaPL\Xml\User\Sections\Ceneosources\Repositories\Contracts\CeneosourceRepository;
use AwemaPL\Xml\User\Sections\Ceneosources\Repositories\EloquentCeneosourceRepository;
use AwemaPL\Xml\Admin\Sections\Settings\Repositories\Contracts\SettingRepository;
use AwemaPL\Xml\Admin\Sections\Settings\Repositories\EloquentSettingRepository;
use AwemaPL\BaseJS\AwemaProvider;
use AwemaPL\Xml\Listeners\EventSubscriber;
use AwemaPL\Xml\Admin\Sections\Installations\Http\Middleware\GlobalMiddleware;
use AwemaPL\Xml\Admin\Sections\Installations\Http\Middleware\GroupMiddleware;
use AwemaPL\Xml\Admin\Sections\Installations\Http\Middleware\Installation;
use AwemaPL\Xml\Admin\Sections\Installations\Http\Middleware\RouteMiddleware;
use AwemaPL\Xml\Contracts\Xml as XmlContract;
use AwemaPL\Xml\User\Sections\Sources\Repositories\Contracts\SourceRepository;
use AwemaPL\Xml\User\Sections\Sources\Repositories\EloquentSourceRepository;
use Illuminate\Support\Facades\Event;
use AwemaPL\Xml\User\Sections\Sources\Models\Source;
use AwemaPL\Xml\User\Sections\Sources\Policies\SourcePolicy;
use AwemaPL\Xml\User\Sections\Ceneosources\Models\Ceneosource;
use AwemaPL\Xml\User\Sections\Ceneosources\Policies\CeneosourcePolicy;
use AwemaPL\Xml\User\Sections\Ceneosources\Services\Contracts\XmlCeneo as XmlCeneoContract;
use AwemaPL\Xml\User\Sections\Ceneosources\Services\XmlCeneo;

class XmlServiceProvider extends AwemaProvider
{

    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Source::class => SourcePolicy::class,
        Ceneosource::class => CeneosourcePolicy::class,
    ];

    public function boot()
    {
        $this->loadTranslationsFrom(__DIR__ . '/../resources/lang', 'xml');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'xml');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->bootMiddleware();
        app('xml')->includeLangJs();
        app('xml')->menuMerge();
        app('xml')->mergePermissions();
        $this->registerPolicies();
        Event::subscribe(EventSubscriber::class);
        parent::boot();
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/xml.php', 'xml');
        $this->mergeConfigFrom(__DIR__ . '/../config/xml-menu.php', 'xml-menu');
        $this->app->bind(XmlContract::class, Xml::class);
        $this->app->singleton('xml', XmlContract::class);
        $this->registerRepositories();
        $this->registerServices();
        parent::register();
    }


    public function getPackageName(): string
    {
        return 'xml';
    }

    public function getPath(): string
    {
        return __DIR__;
    }

    /**
     * Register and bind package repositories
     *
     * @return void
     */
    protected function registerRepositories()
    {
        $this->app->bind(SettingRepository::class, EloquentSettingRepository::class);
        $this->app->bind(SourceRepository::class, EloquentSourceRepository::class);
        $this->app->bind(CeneosourceRepository::class, EloquentCeneosourceRepository::class);
    }

    /**
     * Register and bind package services
     *
     * @return void
     */
    protected function registerServices()
    {
        $this->app->bind(XmlCeneoContract::class, XmlCeneo::class);
    }

    /**
     * Boot middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootMiddleware()
    {
        $this->bootGlobalMiddleware();
        $this->bootRouteMiddleware();
        $this->bootGroupMiddleware();
    }

    /**
     * Boot route middleware
     */
    private function bootRouteMiddleware()
    {
        $router = app('router');
        $router->aliasMiddleware('xml', RouteMiddleware::class);
    }

    /**
     * Boot grEloquentAccountRepositoryoup middleware
     */
    private function bootGroupMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->appendMiddlewareToGroup('web', GroupMiddleware::class);
        $kernel->appendMiddlewareToGroup('web', Installation::class);
    }

    /**
     * Boot global middleware
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    private function bootGlobalMiddleware()
    {
        $kernel = $this->app->make(\Illuminate\Contracts\Http\Kernel::class);
        $kernel->pushMiddleware(GlobalMiddleware::class);
    }
}
