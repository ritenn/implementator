<?php

declare(strict_types=1);

namespace Ritenn\Implementator\Tests\Unit;


use Ritenn\Implementator\Tests\RitennTestCase;

class BindingTest extends RitennTestCase
{
    /**
     * Create layer [Service/Repository] and trigger binding. This is required.
     *
     * @param string $layerBaseName
     * @param string $layerName
     */
    private function canMakeLayerAndBind(string $layerBaseName, string $layerName = 'service') : void
    {
        $this->artisan('make:' . $layerName .' ' . $layerBaseName)
            ->expectsOutput('Success, contract and implementation created.');

        $bindingService = $this->app->make('Ritenn\Implementator\Contracts\BindingContract');
        $bindingService->register();
    }

    /**
     * Test if interface was correctly bind to implementation, by injecting to dummy Controller
     *
     * @param string $controllerName - Dummy controller name
     * @param string $terminologyPlural ['Contracts', 'Interfaces']
     * @param string $typeOfLayerPlural ['Services', 'Repositories]
     * @param bool $withCategorization [@default true] - testing config categorization option
     */
    private function canInjectDependency(
        string $controllerName,
        string $terminologyPlural,
        string $typeOfLayerPlural,
        bool $withCategorization = true
    ) : void
    {
        try {
            $controllerNamespace = '\Ritenn\Implementator\Tests\Dummies\\' . $terminologyPlural . '\\' . $controllerName;
            $testDummyController = $this->app->make($controllerNamespace);
            $categorization = $withCategorization ? 'WithCategorization' : 'WithoutCategorization';

            $layerNamespace = '\App\\' . $typeOfLayerPlural . '\TestInjection' . \Str::singular($terminologyPlural) . $categorization . \Str::singular($typeOfLayerPlural);
            $this->assertTrue($testDummyController->testService instanceof $layerNamespace);
        } catch (\Exception $e)
        {
            $this->assertFalse(true, '[BindingTest] * ' . $controllerNamespace . ' - injection error: ' . $layerNamespace);
        }
    }

    /**
     * @test
     */
    public function canServiceInjectContractWithCategorization()
    {
        $this->app['config']->set('implementator', [
            'terminology' => 'Contracts',
            'contracts_categories' => true,
        ]);

        $this->canMakeLayerAndBind('TestInjectionContractWithCategorization');
        $this->canInjectDependency('DummyServiceWithCategorizationController', 'Contracts', 'Services');
    }

    /**
     * @test
     */
    public function canServiceInjectContractWithoutCategorization()
    {
        $this->app['config']->set('implementator', [
            'terminology' => 'Contracts',
            'contracts_categories' => false,
        ]);

        $this->canMakeLayerAndBind('TestInjectionContractWithoutCategorization');
        $this->canInjectDependency('DummyServiceWithoutCategorizationController', 'Contracts', 'Services', false);
    }

    /**
     * @test
     */
    public function canServiceInjectInterfaceWithCategorization()
    {
        $this->app['config']->set('implementator', [
            'terminology' => 'Interfaces',
            'contracts_categories' => true,
        ]);

        $this->canMakeLayerAndBind('TestInjectionInterfaceWithCategorization');
        $this->canInjectDependency('DummyServiceWithCategorizationController', 'Interfaces', 'Services' );
    }

    /**
     * @test
     */
    public function canServiceInjectInterfaceWithoutCategorization()
    {
        $this->app['config']->set('implementator', [
            'terminology' => 'Interfaces',
            'contracts_categories' => false,
        ]);

        $this->canMakeLayerAndBind('TestInjectionInterfaceWithoutCategorization');
        $this->canInjectDependency('DummyServiceWithoutCategorizationController', 'Interfaces', 'Services', false);
    }

    /**
     * @test
     */
    public function canRepositoryInjectContractWithCategorization()
    {
        $this->app['config']->set('implementator', [
            'terminology' => 'Contracts',
            'contracts_categories' => true,
        ]);

        $this->canMakeLayerAndBind('TestInjectionContractWithCategorization', 'repository');
        $this->canInjectDependency('DummyRepositoryWithCategorizationController', 'Contracts', 'Repositories');
    }

    /**
     * @test
     */
    public function canRepositoryInjectContractWithoutCategorization()
    {
        $this->app['config']->set('implementator', [
            'terminology' => 'Contracts',
            'contracts_categories' => false,
        ]);

        $this->canMakeLayerAndBind('TestInjectionContractWithoutCategorization', 'repository');
        $this->canInjectDependency('DummyRepositoryWithoutCategorizationController', 'Contracts', 'Repositories', false);
    }

    /**
     * @test
     */
    public function canRepositoryInjectInterfaceWithCategorization()
    {
        $this->app['config']->set('implementator', [
            'terminology' => 'Interfaces',
            'contracts_categories' => true,
        ]);

        $this->canMakeLayerAndBind('TestInjectionInterfaceWithCategorization', 'repository');
        $this->canInjectDependency('DummyRepositoryWithCategorizationController', 'Interfaces', 'Repositories');
    }

    /**
     * @test
     */
    public function canRepositoryInjectInterfaceWithoutCategorization()
    {
        $this->app['config']->set('implementator', [
            'terminology' => 'Interfaces',
            'contracts_categories' => false,
        ]);

        $this->canMakeLayerAndBind('TestInjectionInterfaceWithoutCategorization', 'repository');
        $this->canInjectDependency('DummyRepositoryWithoutCategorizationController', 'Interfaces', 'Repositories', false);
    }

}