<?php
declare(strict_types=1);

namespace Ritenn\Implementator;


abstract class ImplementatorBase {

    /**
     * Project base path
     * @var string
     */
    public $baseAppPath;

    /**
     * Folder and class prefix name for interfaces
     * by default it's set to 'Contracts', but can be changed to 'Interfaces'
     * in config file ('config/implementator').
     *
     * @var string
     */
    public $interfaceTerminology;

    /**
     * If it's true then contracts/interfaces will be categorized with folders
     * by default it's set to true, but in config file ('config/implementator').
     *
     * @var bool
     */
    public $isContractsCategorizationEnabled;

    public function __construct()
    {
        $this->baseAppPath = base_path() . "/app/";
        $this->validateConfigSetTerminology();
        $this->validateConfigSetContractsCategorization();
    }

    /**
     * check if code is executed from console - function shortcut.
     *
     * @return bool
     */
    public function isCLI() : bool
    {
        return app()->runningInConsole();
    }

    /**
     * @param string $typeOfLayer
     * @param string $fileNameBase
     *
     * @return string
     */
    public function getImplementationFileFullPath(string $typeOfLayer, string $fileNameBase) : string
    {
        $basePath = $typeOfLayer === 'Services' ? $this->getServiceImplementationBasePath() : $this->getRepositoriesImplementationBasePath();

        return $basePath . '/' . $this->getFileNameWithExtension($fileNameBase, false, $typeOfLayer);
    }

    /**
     * @param string $typeOfLayer
     * @param string $fileNameBase
     *
     * @return string
     */
    public function getContractFileFullPath(string $typeOfLayer, string $fileNameBase) : string
    {
        return $this->getContractsBasePathByLayer($typeOfLayer) . '/' . $this->getFileNameWithExtension($fileNameBase, true, $typeOfLayer);
    }

    /**
     * Sets name for interfaces folder
     */
    public function validateConfigSetTerminology() : void
    {
        $terminology = config('implementator.terminology');
        $this->interfaceTerminology = in_array($terminology, array('Contracts', 'Interfaces')) ? $terminology : 'Contracts';
    }

    /**
     * Sets contracts categorization settings value
     */
    public function validateConfigSetContractsCategorization() : void
    {
        $contractsCategories =  config('implementator.contracts_categories');
        $this->isContractsCategorizationEnabled = is_bool($contractsCategories) ? $contractsCategories : true;
    }

    /**
     * @param bool $isContract
     * @param string $typeOfLayer
     *
     * @return string
     */
    public function getNamespace(bool $isContract, string $typeOfLayer) : string
    {
        if ($isContract && !$this->isContractsCategorizationEnabled)
        {
            return "App\\" . $this->interfaceTerminology;
        }

        return $isContract ? "App\\" . $this->interfaceTerminology . "\\" . $typeOfLayer : "App\\" . $typeOfLayer;
    }

    /**
     * @param string $fileNameBase
     * @param bool $isContract
     * @param string $typeOfLayer
     *
     * @return string
     */
    public function getClassName(string $fileNameBase, bool $isContract, string $typeOfLayer) : string
    {
        return $isContract ? $fileNameBase . \Str::singular($this->interfaceTerminology) : $fileNameBase . \Str::singular($typeOfLayer);
    }

    /**
     * @param string $filename
     * @param string $base
     * @return string
     *
     * @throws \Exception
     */
    public function getFilenameBase(string $filename, string $base) : string
    {
        $terminologyPrefix = \Str::singular($base);
        $terminologyStartPos = strpos($filename, $terminologyPrefix);


        if ($terminologyStartPos !== false)
        {
            return substr($filename, 0, $terminologyStartPos);

        } else {

            throw new \Exception('File doesn\'t contains terminology prefix, check implementator config file (config/implementator.php).');
        }
    }

    /**
     * @param string $fileNameBase
     * @param bool $isContract
     * @param string $typeOfLayer
     *
     * @return string
     */
    public function getFileNameWithExtension(string $fileNameBase, bool $isContract, string $typeOfLayer) : string
    {
        return $this->getClassName($fileNameBase, $isContract, $typeOfLayer) . '.php';
    }

    /**
     * @param bool $isContract
     * @param string $typeOfLayer
     *
     * @return string
     */
    public function getFolderPath(bool $isContract, string $typeOfLayer) : string
    {
        if ($isContract)
        {
            return $this->getContractsBasePathByLayer($typeOfLayer);
        }

        return $typeOfLayer === 'Services' ? $this->getServiceImplementationBasePath() : $this->getRepositoriesImplementationBasePath();
    }

    /**
     * @param string $typeOfLayer - required because of categorization option
     *
     * @return string
     */
    public function getContractsBasePathByLayer(string $typeOfLayer) : string
    {
        if ($this->isContractsCategorizationEnabled)
        {
            return $this->getContractsBasePath() . '/' . $typeOfLayer;
        }

        return $this->getContractsBasePath();
    }

    /**
     * @return string
     */
    public function getContractsBasePath() : string
    {
        return $this->baseAppPath . $this->interfaceTerminology;
    }

    /**
     * @return string
     */
    public function getServiceImplementationBasePath() : string
    {
        return $this->baseAppPath . 'Services';
    }

    /**
     * @return string
     */
    public function getRepositoriesImplementationBasePath() : string
    {
        return $this->baseAppPath . 'Repositories';
    }

    /**
     * @param string $fullPath
     *
     * @return bool
     */
    public function checkIfFolderExists(string $fullPath) : bool
    {
        return \File::isDirectory($fullPath);
    }

    /**
     * @param string $fullPath
     *
     * @return bool
     */
    public function checkIfFileExists(string $fullPath) : bool
    {
        return \File::exists($fullPath);
    }
}