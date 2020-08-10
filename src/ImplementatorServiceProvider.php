<?php

declare(strict_types=1);

namespace Ritenn\Implementator;


use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Ritenn\Implementator\Commands\MakeRepositoryClass;
use Ritenn\Implementator\Commands\MakeServiceClass;
use Ritenn\Implementator\Contracts\BindingContract;

class ImplementatorServiceProvider extends ServiceProvider
{
    /**
     * Real time binding or based on cache data
     *
     * @param BindingContract $bindingService
     * @throws \Exception
     */
    public function boot(BindingContract $bindingService)
    {
        $this->setCommands();
        $this->setConfig();

        /**
         * Implements all created Contracts/Interfaces to Services and/or Repositories
         */
        if ($bindingService->canLoadFromCache() && $this->isProduction()) {

            foreach ($bindingService->cachedBindings as $binding)
            {
                $binding = collect($binding);
                $this->app->bind($binding->first(), $binding->last());
            }

        } else {
            $bindingService->resetCache();
            $bindingService->register();
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->bindPackageInterfaces();
    }


    /**
     * Set configs & allows to publish
     */
    private function setConfig() : void
    {
        $vendorPath = __DIR__ . '/Config/implementator.php';

        $this->publishes([
            $vendorPath  => config_path('implementator.php')
        ]);

        $this->mergeConfigFrom(
            $vendorPath, 'implementator'
        );

    }

    /**
     * Checks environment
     * Commands are allowed only in local/dev environment
     *
     * @return bool
     */
    private function isProduction() : bool
    {
        return in_array(config('app.env'), ['prod', 'production']) || !config('app.debug');
    }

    /**
     * Set commands
     */
    private function setCommands() : void
    {
        if ($this->app->runningInConsole() && !$this->isProduction()) {
            $this->commands([
                MakeServiceClass::class,
                MakeRepositoryClass::class
            ]);
        }
    }

    /**
     * Binds package interfaces to implementations
     */
    private function bindPackageInterfaces()
    {
        $this->app->bind(
            \Ritenn\Implementator\Contracts\BindingContract::class,
            \Ritenn\Implementator\Services\BindingService::class
        );

        if (!$this->isProduction())
        {
            $this->app->bind(
                \Ritenn\Implementator\Contracts\ProcessCreateClassContract::class,
                \Ritenn\Implementator\Services\ProcessCreateClassService::class
            );
        }
    }
}
