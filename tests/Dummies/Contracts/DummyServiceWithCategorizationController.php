<?php

namespace Ritenn\Implementator\tests\Dummies\Contracts;


use \App\Contracts\Services\TestInjectionContractWithCategorizationContract;
use \Illuminate\Routing\Controller;

class DummyServiceWithCategorizationController extends Controller
{
    public $testService;

    public function __construct(TestInjectionContractWithCategorizationContract $testService)
    {
        $this->testService = $testService;
    }

}
