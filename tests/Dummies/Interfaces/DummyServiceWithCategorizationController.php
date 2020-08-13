<?php

namespace Ritenn\Implementator\Tests\Dummies\Interfaces;

use App\Interfaces\Services\TestInjectionInterfaceWithCategorizationInterface;
use Illuminate\Routing\Controller;

class DummyServiceWithCategorizationController extends Controller
{
    public $testService;

    public function __construct(TestInjectionInterfaceWithCategorizationInterface $testService)
    {
        $this->testService = $testService;
    }

}
