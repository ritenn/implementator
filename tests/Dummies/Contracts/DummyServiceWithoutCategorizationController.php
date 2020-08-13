<?php

namespace Ritenn\Implementator\Tests\Dummies\Contracts;


use App\Contracts\TestInjectionContractWithoutCategorizationContract;
use \Illuminate\Routing\Controller;

class DummyServiceWithoutCategorizationController extends Controller
{
    public $testService;

    public function __construct(TestInjectionContractWithoutCategorizationContract $testService)
    {
        $this->testService = $testService;
    }

}
