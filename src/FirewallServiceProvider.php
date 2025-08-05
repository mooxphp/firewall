<?php

declare(strict_types=1);

namespace Moox\Firewall;

use Moox\Firewall\Middleware\FirewallMiddleware;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FirewallServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('firewall')
            ->hasConfigFile()
            ->hasViews('firewall')
            ->hasTranslations()
            ->hasMigrations()
            ->hasCommands();
    }

    public function packageBooted(): void
    {
        $this->app['router']->aliasMiddleware('firewall', FirewallMiddleware::class);

        if (config('firewall.global_enabled', false)) {
            $this->app['router']->pushMiddlewareToGroup('web', FirewallMiddleware::class);
            $this->app['router']->pushMiddlewareToGroup('api', FirewallMiddleware::class);
        }
    }

    public function boot(): void
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'firewall');
    }
}
