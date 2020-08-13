<?php

declare(strict_types=1);

namespace Ritenn\Implementator\Tests\Unit;


use Ritenn\Implementator\Tests\RitennTestCase;

class MakeLayerTest extends RitennTestCase
{

    /**
     * @test
     */
    public function canMakeServiceDefault()
    {
        $this->artisan('make:service Test')
            ->expectsOutput('Success interface and implementation created.');

        $this->assertFileExists($this->workbenchAppDir . '/Services/TestService.php');
        $this->assertFileExists($this->workbenchAppDir . '/Contracts/Services/TestContract.php');
    }

    /**
     * @test
     */
    public function canMakeRepositoryDefault()
    {
        $this->artisan('make:repository Test')
            ->expectsOutput('Success interface and implementation created.');

        $this->assertFileExists($this->workbenchAppDir . '/Repositories/TestRepository.php');
        $this->assertFileExists($this->workbenchAppDir . '/Contracts/Repositories/TestContract.php');
    }

    /**
     * @test
     */
    public function canMakeServiceWithoutCategorization()
    {
        config(['implementator.contracts_categories' => false]);

        $this->artisan('make:service Test')
            ->expectsOutput('Success interface and implementation created.');

        $this->assertFileExists($this->workbenchAppDir . '/Services/TestService.php');
        $this->assertFileExists($this->workbenchAppDir . '/Contracts/TestContract.php');
    }

    /**
     * @test
     */
    public function canMakeRepositoryWithoutCategorization()
    {
        config(['implementator.contracts_categories' => false]);

        $this->artisan('make:repository Test')
            ->expectsOutput('Success interface and implementation created.');

        $this->assertFileExists($this->workbenchAppDir . '/Repositories/TestRepository.php');
        $this->assertFileExists($this->workbenchAppDir . '/Contracts/TestContract.php');
    }

    /**
     * @test
     */
    public function canMakeServiceWithoutCategorizationAsInterfaces()
    {
        config([
            'implementator.terminology' => 'Interfaces',
            'implementator.contracts_categories' => false
        ]);

        $this->artisan('make:service Test')
            ->expectsOutput('Success interface and implementation created.');

        $this->assertFileExists($this->workbenchAppDir . '/Services/TestService.php');
        $this->assertFileExists($this->workbenchAppDir . '/Interfaces/TestInterface.php');
    }

    /**
     * @test
     */
    public function canMakeRepositoryWithoutCategorizationAsInterfaces()
    {
        config([
            'implementator.terminology' => 'Interfaces',
            'implementator.contracts_categories' => false
        ]);

        $this->artisan('make:repository Test')
            ->expectsOutput('Success interface and implementation created.');

        $this->assertFileExists($this->workbenchAppDir . '/Repositories/TestRepository.php');
        $this->assertFileExists($this->workbenchAppDir . '/Interfaces/TestInterface.php');
    }

    /**
     * @test
     */
    public function canMakeServiceAsInterfaces()
    {
        config([
            'implementator.terminology' => 'Interfaces',
            'implementator.contracts_categories' => true
        ]);

        $this->artisan('make:service Test')
            ->expectsOutput('Success interface and implementation created.');

        $this->assertFileExists($this->workbenchAppDir . '/Services/TestService.php');
        $this->assertFileExists($this->workbenchAppDir . '/Interfaces/Services/TestInterface.php');
    }

    /**
     * @test
     */
    public function canMakeRepositoryAsInterfaces()
    {
        config([
            'implementator.terminology' => 'Interfaces',
            'implementator.contracts_categories' => true
        ]);

        $this->artisan('make:repository Test')
            ->expectsOutput('Success interface and implementation created.');

        $this->assertFileExists($this->workbenchAppDir . '/Repositories/TestRepository.php');
        $this->assertFileExists($this->workbenchAppDir . '/Interfaces/Repositories/TestInterface.php');
    }

}