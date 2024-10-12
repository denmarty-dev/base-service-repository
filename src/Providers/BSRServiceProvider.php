<?php

namespace Denmarty\BaseServiceRepository\Providers;

use Denmarty\BaseServiceRepository\BaseService\BaseRepository;
use Denmarty\BaseServiceRepository\BaseService\BaseRepositoryInterface;
use Denmarty\BaseServiceRepository\Console\Commands\MakeServiceCommand;
use Illuminate\Support\ServiceProvider;

class BSRServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->app->bind(BaseRepositoryInterface::class, BaseRepository::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeServiceCommand::class,
            ]);
        }
    }
}
