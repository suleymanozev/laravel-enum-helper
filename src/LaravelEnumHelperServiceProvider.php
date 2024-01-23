<?php

declare(strict_types=1);

namespace Suleymanozev\LaravelEnumHelper;

use Illuminate\Support\ServiceProvider;
use Suleymanozev\LaravelEnumHelper\Commands\EnumAnnotateCommand;

class LaravelEnumHelperServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->bootCommands();
    }

    protected function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                EnumAnnotateCommand::class,
            ]);
        }
    }
}
