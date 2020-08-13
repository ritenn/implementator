<?php
declare(strict_types=1);

namespace Ritenn\Implementator\Services;


use Ritenn\Implementator\Contracts\ProcessCreateClassContract;
use Ritenn\Implementator\ImplementatorBase;

class ProcessCreateClassService extends ImplementatorBase implements ProcessCreateClassContract {

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
                'message' => 'Command should be called only from CLI as this is DEV tool.'
            ];
        };

        if ( $this->checkIfFileExists($this->getContractFileFullPath($typeOfLayer, $fileName)) &&
            $this->checkIfFileExists($this->getImplementationFileFullPath($typeOfLayer, $fileName)) )
        {
            return [
                'error' => true,
                'message' => 'Class and implementation already exists.'
            ];
        }

        if ( $this->createInterface($typeOfLayer, $fileName) && $this->createClassImplementation($typeOfLayer, $fileName) )
        {
            return [
                'error' => false,
                'message' => 'Success interface and implementation created.'
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