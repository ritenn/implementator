<?php

namespace Ritenn\Implementator\Services;


use Ritenn\Implementator\Contracts\ProcessCreateClassContract;

class ProcessCreateClassService implements ProcessCreateClassContract {

    /**
     * Project base path
     * @var string
     */
    private $baseAppPath;

    public function __construct()
    {
        $this->baseAppPath = base_path() . "/app/";
    }

    /**
     * Make interface and implement to class
     *
     * @param string $typeOfClass
     * @param string $fileName
     *
     * @return array
     */
    public function make(string $typeOfClass, string $fileName) : array
    {
        if (!$this->isCLI())
        {
            return [
                'error' => true,
                'message' => 'Command should be called only from CLI as this is DEV tool.'
            ];
        };

        if ( $this->checkIfFileExists($this->getContractFullPath($typeOfClass, $fileName)) &&
            $this->checkIfFileExists($this->getImplementationFullPath($typeOfClass, $fileName)) )
        {
            return [
                'error' => true,
                'message' => 'Class and implementation already exists.'
            ];
        }

        if ( $this->createInterface($typeOfClass, $fileName) && $this->createClassImplementation($typeOfClass, $fileName) )
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
     * Check if class is being executed from CLI.
     *
     * @return bool
     */
    public function isCLI() : bool
    {
       return app()->runningInConsole();
    }

    /**
     * Creates interface.
     * 
     * @param string $typeOfClass
     * @param string $fileName
     * 
     * @return bool
     */
    public function createInterface(string $typeOfClass, string $fileName) : bool
    {
        $name = $fileName  . 'Contract';

        $template = "<?php\n";
        $template .= "\r\nnamespace App\Contracts\\" . $typeOfClass . ";\n\n";
        $template .= "\r\ninterface " . $name . " {\n\n";
        $template .= "\r\n}";

        return $this->createFile($typeOfClass, $fileName, $template, true);
    }

    /**
     * Creates interface implementation.
     *
     * @param string $typeOfClass
     * @param string $fileName
     *
     * @return bool
     */
    public function createClassImplementation(string $typeOfClass, string $fileName) : bool
    {
        $contractName = $fileName  . "Contract";
        $className = $fileName  . \Str::singular($typeOfClass);

        $template = "<?php\n";
        $template .= "\r\nnamespace App\\" . $typeOfClass . ";\n\n";
        $template .= "\r\nuse App\Contracts\\" . $typeOfClass . "\\" . $contractName . ";\n";
        $template .= "\r\nclass " . $className . " implements " . $contractName  . " {\n\n";
        $template .= "\r\n}";

        return $this->createFile($typeOfClass, $fileName, $template);
    }

    /**
     * Creates directory (if needed) and file based on $typeOfClass
     *
     * @param string $typeOfClass
     * @param string $fileName
     * @param String $content - file content
     * @param bool $isContract (default: false)
     *
     * @return bool
     */
    public function createFile(string $typeOfClass, string $fileName, String $content, bool $isContract = false) : bool
    {

        $path = $isContract ? $this->baseAppPath . 'Contracts/' . $typeOfClass : $this->baseAppPath . $typeOfClass;
        $name = $isContract ? $fileName . 'Contract.php' : $fileName . \Str::singular($typeOfClass) . '.php';
        $fullFilePath = $path . '/' . $name;

        $this->createFolder($path);

        if ($this->checkIfFolderExists($path) && !$this->checkIfFileExists($fullFilePath))
        {
            return \File::put($fullFilePath, $content) ?? false;
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

    /**
     * @param string $fullPath
     *
     * @return bool
     */
    public function checkIfFolderExists(string $fullPath) : bool
    {
        return \File::exists($fullPath);
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

    /**
     * @param string $typeOfClass
     * @param string $filename
     *
     * @return string
     */
    private function getImplementationFullPath(string $typeOfClass, string $filename) : string
    {
        return $this->baseAppPath . $typeOfClass . '/' . $filename . \Str::singular($typeOfClass) . '.php';
    }

    /**
     * @param string $typeOfClass
     * @param string $filename
     *
     * @return string
     */
    private function getContractFullPath(string $typeOfClass, string $filename) : string
    {
        return $this->baseAppPath . 'Contracts/' . $typeOfClass . '/' . $filename . 'Contract.php';
    }
}