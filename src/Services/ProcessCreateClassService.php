<?php
declare(strict_types=1);

namespace Ritenn\Implementator\Services;


use Ritenn\Implementator\Contracts\BindingContract;
use Ritenn\Implementator\Contracts\ProcessCreateClassContract;
use Ritenn\Implementator\ImplementatorBase;

class ProcessCreateClassService extends ImplementatorBase implements ProcessCreateClassContract {

    /**
     * @var Ritenn\Implementator\Contracts\BindingContract
     */
    private $bindingService;

    public function __construct(BindingContract $bindingService)
    {
        parent::__construct();
        $this->bindingService = $bindingService;
    }

    /**
     * Make interface and implement to class
     *
     * @param string $typeOfLayer
     * @param string $fileName
     *
     * @return array
     */
    public function make(string $typeOfLayer, string $fileName) : array
    {
        if (!$this->isCLI())
        {
            return [
                'error' => true,
                'message' => 'Command should be called only from CLI.'
            ];
        };

        if ( $this->checkIfFileExists($this->getContractFileFullPath($typeOfLayer, $fileName)) &&
            $this->checkIfFileExists($this->getImplementationFileFullPath($typeOfLayer, $fileName)) )
        {
            return [
                'error' => true,
                'message' => 'Contract and implementation already exists.'
            ];
        }

        if ( $this->createInterface($typeOfLayer, $fileName) && $this->createClassImplementation($typeOfLayer, $fileName) )
        {
            return [
                'error' => false,
                'message' => 'Success, contract and implementation created.'
            ];
        }

        return [
            'error' => true,
            'message' => 'Couldn\'t create directories and/or files. Check permissions and try again.'
        ];
    }

    /**
     * Creates only layer - Service or Repository without contract
     *
     * @param string $typeOfLayer
     * @param string $fileName
     *
     * @return array
     */
    public function makeOnlyLayer(string $typeOfLayer, string $fileName) : array
    {
        if (!$this->isCLI())
        {
            return [
                'error' => true,
                'message' => 'Command should be called only from CLI.'
            ];
        };

        if ( $this->checkIfFileExists($this->getImplementationFileFullPath($typeOfLayer, $fileName)) )
        {
            return [
                'error' => true,
                'message' => 'Layer already exists.'
            ];
        }

        if ( $this->createLayer($typeOfLayer, $fileName) )
        {
            return [
                'error' => false,
                'message' => 'Success, layer class created.'
            ];
        }

        return [
            'error' => true,
            'message' => 'Couldn\'t create directories and/or files. Check permissions and try again.'
        ];
    }

    /**
     * Make just contract
     *
     * @param string $typeOfLayer
     * @param string $fileName
     *
     * @return array
     */
    public function makeOnlyContract(string $fileName, string $typeOfLayer = '') : array
    {
        if (!$this->isCLI())
        {
            return [
                'error' => true,
                'message' => 'Command should be called only from CLI.'
            ];
        };

        if ( $this->checkIfFileExists($this->getContractFileFullPath($typeOfLayer, $fileName)))
        {
            return [
                'error' => true,
                'message' => 'Contract already exists.'
            ];
        }

        if ( $this->createInterface($typeOfLayer, $fileName))
        {
            return [
                'error' => false,
                'message' => 'Success, contract created.'
            ];
        }

        return [
            'error' => true,
            'message' => 'Couldn\'t create directories and/or files. Check permissions and try again.'
        ];
    }

    /**
     * Creates interface.
     * 
     * @param string $typeOfLayer
     * @param string $fileName
     * 
     * @return bool
     */
    public function createInterface(string $typeOfLayer, string $fileName) : bool
    {
        $namespace = $this->getNamespaceByLayerName(true, $typeOfLayer);
        $className = $this->getClassName($fileName, true, $typeOfLayer);

        $template = "<?php\n";
        $template .= "\r\nnamespace " . $namespace . ";\n\n";
        $template .= "\r\ninterface " . $className . " {\n\n";
        $template .= "\r\n}";

        return $this->createFile($typeOfLayer, $fileName, $template, true);
    }

    /**
     * Creates interface implementation.
     *
     * @param string $typeOfLayer
     * @param string $fileName
     *
     * @return bool
     */
    public function createClassImplementation(string $typeOfLayer, string $fileName) : bool
    {
        $namespace = $this->getNamespaceByLayerName(false, $typeOfLayer);
        $contractName = $this->getClassName($fileName, true, $typeOfLayer);;
        $className = $this->getClassName($fileName, false, $typeOfLayer);
        $contractNamespace = $this->getContractNamespaceByLayerClassName($className);

        $template = "<?php\n";
        $template .= "\r\nnamespace " . $namespace . ";\n\n";
        $template .= "\r\nuse " . $contractNamespace . ";\n";
        $template .= "\r\nclass " . $className . " implements " . $contractName  . " {\n\n";
        $template .= "\r\n}";

        return $this->createFile($typeOfLayer, $fileName, $template);
    }

    /**
     * Create only layer file without contract
     *
     * @param string $typeOfLayer
     * @param string $fileName
     *
     * @return bool
     */
    public function createLayer(string $typeOfLayer, string $fileName) : bool
    {
        $namespace = $this->getNamespaceByLayerName(false, $typeOfLayer);
        $className = $this->getClassName($fileName, false, $typeOfLayer);

        $template = "<?php\n";
        $template .= "\r\nnamespace " . $namespace . ";\n\n";
        $template .= "\r\nclass " . $className . " {\n\n";
        $template .= "\r\n}";

        return $this->createFile($typeOfLayer, $fileName, $template);
    }

    /**
     * Creates directory (if needed) and file based on $typeOfLayer
     *
     * @param string $typeOfLayer
     * @param string $fileName
     * @param String $content - file content
     * @param bool $isContract (default: false)
     *
     * @return bool
     */
    public function createFile(string $typeOfLayer, string $fileName, String $content, bool $isContract = false) : bool
    {
        //Reset cached bindings as we create new file.
        $this->bindingService->resetCache();

        $path = $this->getFolderPath($isContract, $typeOfLayer);
        $filenameWithExtension = $this->getFileNameWithExtension($fileName, $isContract, $typeOfLayer);
        $fullFilePath = $path . '/' . $filenameWithExtension;

        $this->createFolder($path);

        if ($this->checkIfFolderExists($path) && !$this->checkIfFileExists($fullFilePath))
        {
            return (bool) \File::put($fullFilePath, $content) ?? false;
        }

        return false;
    }

    /**
     * Creates folder based on given $fullPath.
     *
     * @param string $fullPath
     *
     * @return bool
     */
    public function createFolder(string $fullPath) : bool
    {

        if (!$this->checkIfFolderExists($fullPath))
        {
            return \File::makeDirectory($fullPath, 0755, true, true) ?? false;
        }

        return false;
    }




}