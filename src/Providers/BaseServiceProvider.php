<?php

namespace Denmarty\BaseServiceRepository\Providers;

use Denmarty\BaseServiceRepository\Console\Commands\MakeServiceCommand;
use Illuminate\Support\ServiceProvider;

class BaseServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeServiceCommand::class,
            ]);
        }
    }
}
