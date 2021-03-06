<?php

namespace Ritenn\Implementator\Commands;


use Illuminate\Console\Command;
use Ritenn\Implementator\Contracts\ProcessCreateClassContract;

class MakeRepositoryClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository 
    {name : Repository file name}  
    {--without-contract : create layer without contract implementation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates interface and implementation files.';

    private $processCreateClassService;

    /**
     * MakeServiceClass constructor.
     * @param ProcessCreateClassContract $processCreateClassService
     */
    public function __construct(ProcessCreateClassContract $processCreateClassService)
    {
        parent::__construct();

        $this->processCreateClassService = $processCreateClassService;
    }

    /**
     * Execute command
     */
    public function handle()
    {
        $className = $this->argument('name');
        $onlyLayer = $this->option('without-contract');

        $result = $onlyLayer ? $this->processCreateClassService->makeOnlyLayer("Repositories", $className) :
                               $this->processCreateClassService->make("Repositories", $className);

        $messageType = $result['error'] ?  'error' : 'info';
        $message = $result['message'] ?? "Unknown error, please report it to package author";

        $this->$messageType($message);
    }
}
