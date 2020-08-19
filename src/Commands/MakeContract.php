<?php

namespace Ritenn\Implementator\Commands;


use Illuminate\Console\Command;
use Ritenn\Implementator\Contracts\ProcessCreateClassContract;

class MakeContract extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:contract {name : Contract base file name} {--layer= : Layer name - Services|Repositories}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates contract/interface file (You can change terminology in config).';

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
        $layerName = $this->option('layer');

        if (config('implementator.contracts_categories') && !in_array($layerName, ['Services', 'Repositories']))
        {
            return $this->error('Categorization is enabled, please pass layer name as \'--layer=\' parameter. Available options - Services or Repositories');
        }

        $result = $layerName === null ? $this->processCreateClassService->makeOnlyContract($className) :
                                        $this->processCreateClassService->makeOnlyContract($className, $layerName);

        $messageType = $result['error'] ? 'error' : 'info';
        $message = $result['message'] ?? "Unknown error, please report it to package author";

        $this->$messageType($message);
    }
}
