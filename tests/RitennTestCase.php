<?php

declare(strict_types=1);

namespace Ritenn\Implementator\Tests;


use Illuminate\Console\Application;
use Illuminate\Filesystem\Filesystem;
use Orchestra\Testbench\TestCase;
use Ritenn\Implementator\Commands\MakeRepositoryClass;
use Ritenn\Implementator\Commands\MakeServiceClass;
use Ritenn\Implementator\Contracts\BindingContract;

abstract class RitennTestCase extends TestCase
{
    /**
     * @var $workbenchAppDir - app test directory
     */
    public $workbenchAppDir;
    public $bindingService;
    public function setUp(): void
    {
        parent::setUp();
        $this->workbenchAppDir = base_path() . '/app';
    }

    /**
     * Before next test
     */
    public function tearDown(): void
    {
        parent::tearDown();
        $this->clearWorkbenchAppDirectory();
    }

    /**
     * Reset workbench's app directory to initial state
     */
    public function clearWorkbenchAppDirectory() : void
    {
        $filesystem = new Filesystem;
        $filesystem->cleanDirectory($this->workbenchAppDir);
    }

//    protected function getPackageProviders($app)
//    {
//        return ['Ritenn\Implementator\ImplementatorServiceProvider'];
//    }

    /**
     * Setup workbench environment
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->clearWorkbenchAppDirectory();

        $app->bind(
            \Ritenn\Implementator\Contracts\BindingContract::class,
            \Ritenn\Implementator\Services\BindingService::class
        );

        $app->bind(
            \Ritenn\Implementator\Contracts\ProcessCreateClassContract::class,
            \Ritenn\Implementator\Services\ProcessCreateClassService::class
        );

        Application::starting(function ($artisan) {
            $artisan->add(app(MakeServiceClass::class));
            $artisan->add(app(MakeRepositoryClass::class));
        });

        $app['config']->set('implementator', [
            'terminology' => 'Contracts',
            'contracts_categories' => true,
            'cache' => true
        ]);

    }

}