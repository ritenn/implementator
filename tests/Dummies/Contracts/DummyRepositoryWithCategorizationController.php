<?php

namespace Ritenn\Implementator\tests\Dummies\Contracts;


use \App\Contracts\Repositories\TestInjectionContractWithCategorizationContract;
use \Illuminate\Routing\Controller;

class DummyRepositoryWithCategorizationController extends Controller
{
    public $testService;

    public function __construct(TestInjectionContractWithCategorizationContract $testService)
    {
        $this->testService = $testService;
    }

}
