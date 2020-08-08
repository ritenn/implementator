<?php

namespace Ritenn\Implementator\Contracts;


interface ProcessCreateClassContract {

    /**
     * Make interface and implement to class
     *
     * @param string $typeOfClass
     * @param string $fileName
     *
     * @return array
     */
    public function make(string $typeOfClass, string $fileName) : array;

    /**
     * Check if class is being executed from CLI.
     *
     * @return bool
     */
    public function isCLI() : bool;

    /**
     * Creates interface.
     *
     * @param string $typeOfClass
     * @param string $fileName
     *
     * @return bool
     */
    public function createInterface(string $typeOfClass, string $fileName) : bool;

    /**
     * Creates interface implementation.
     *
     * @param string $typeOfClass
     * @param string $fileName
     *
     * @return bool
     */
    public function createClassImplementation(string $typeOfClass, string $fileName) : bool;

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
    public function createFile(string $typeOfClass, string $fileName, String $content, bool $isContract = false) : bool;

    /**
     * Creates folder based on given $fullPath.
     *
     * @param string $fullPath
     *
     * @return bool
     */
    public function createFolder(string $fullPath) : bool;

    /**
     * @param string $fullPath
     *
     * @return bool
     */
    public function checkIfFolderExists(string $fullPath) : bool;

    /**
     * @param string $fullPath
     *
     * @return bool
     */
    public function checkIfFileExists(string $fullPath) : bool;
}