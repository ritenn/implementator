<?php

namespace Ritenn\Implementator\Tests\Dummies\Interfaces;

use App\Interfaces\TestInjectionInterfaceWithoutCategorizationInterface;
use Illuminate\Routing\Controller;

class DummyServiceWithoutCategorizationController extends Controller
{
    public $testService;

    public function __construct(TestInjectionInterfaceWithoutCategorizationInterface $testService)
    {
        $this->testService = $testService;
    }

}
