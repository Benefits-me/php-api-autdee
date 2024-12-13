<?php

declare(strict_types=1);

namespace BenefitsMe\ApiAuth\Provider;

use BenefitsMe\ApiAuth\Services\AuthService;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom($this->determineConfigFile(), 'api-auth');

        $this->app->singleton(AuthService::class, function ($app) {
            return new AuthService();
        });
    }

    public function boot(): void
    {
        $this->publishes([
            $this->determineConfigFile() => config_path('api-auth.php'),
        ], 'api-auth-config');
    }

    private function determineConfigFile(): string
    {
        return realpath(__DIR__ . '/../..') . '/config/api-auth.php';
    }

}
