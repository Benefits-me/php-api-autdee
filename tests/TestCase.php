<?php

namespace Tests;

use BenefitsMe\ApiAuth\Provider\AuthServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            AuthServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        // Stellen Sie sicher, dass die Konfiguration geladen wird
        $app['config']->set('api-auth', require __DIR__ . '/../config/api-auth.php');
    }
}
