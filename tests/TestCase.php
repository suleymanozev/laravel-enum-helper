<?php

declare(strict_types=1);

namespace Suleymanozev\LaravelEnumHelper\Tests;

use Orchestra\Testbench\TestCase as TestbenchTestCase;
use Suleymanozev\LaravelEnumHelper\LaravelEnumHelperServiceProvider;

class TestCase extends TestbenchTestCase
{
    protected string $withoutDocBlockEnumsFolder = __DIR__.'/Support/WithoutDocBlockEnums';

    protected string $enumsFolder = __DIR__.'/Support/Enums';

    protected function getEnvironmentSetUp($app)
    {
        $app['path.lang'] = __DIR__.'/lang';
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelEnumHelperServiceProvider::class,
        ];
    }
}
