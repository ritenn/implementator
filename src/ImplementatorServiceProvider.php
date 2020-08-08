<?php

declare(strict_types=1);

namespace Ritenn\Implementator;


use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Ritenn\Implementator\Commands\MakeRepositoryClass;
use Ritenn\Implementator\Commands\MakeServiceClass;

class ImplementatorServiceProvider extends ServiceProvider
{
    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {
        //Commands are allowed only in local/dev
        if (!$this->isProduction()) {
            $this->bindCommands();
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //Commands are allowed only in local/dev
        if (!$this->isProduction())
        {
            //Bind commands required logic
            $this->app->bind(
                \Ritenn\Implementator\Contracts\ProcessCreateClassContract::class,
                \Ritenn\Implementator\Services\ProcessCreateClassService::class
            );
        }
    }

    public function isProduction() : bool
    {
        return in_array(config('app.env'), ['prod', 'production']) || !config('app.debug');
    }

    private function bindCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeServiceClass::class,
                MakeRepositoryClass::class
            ]);
        }
    }
}
