<?php

namespace LaracraftTech\LaravelDateScopes\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaracraftTech\LaravelDateScopes\LaravelDateScopesServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'LaracraftTech\\LaravelDateScopes\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelDateScopesServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-date-scopes_table.php.stub';
        $migration->up();
        */
    }
}
