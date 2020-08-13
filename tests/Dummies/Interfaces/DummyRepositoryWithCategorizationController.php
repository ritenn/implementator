<?php

namespace Ritenn\Implementator\Tests\Dummies\Interfaces;

use App\Interfaces\Repositories\TestInjectionInterfaceWithCategorizationInterface;
use Illuminate\Routing\Controller;

class DummyRepositoryWithCategorizationController extends Controller
{
    public $testService;

    public function __construct(TestInjectionInterfaceWithCategorizationInterface $testService)
    {
        $this->testService = $testService;
    }

}
