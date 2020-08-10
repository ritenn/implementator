<?php

namespace Ritenn\Implementator\Contracts;


interface ProcessCreateClassContract {

    /**
     * Make interface and implement to class
     *
     * @param string $typeOfLayer
     * @param string $fileName
     *
     * @return array
     */
    public function make(string $typeOfLayer, string $fileName) : array;

    /**
     * Creates interface.
     *
     * @param string $typeOfLayer
     * @param string $fileName
     *
     * @return bool
     */
    public function createInterface(string $typeOfLayer, string $fileName) : bool;

    /**
     * Creates interface implementation.
     *
     * @param string $typeOfLayer
     * @param string $fileName
     *
     * @return bool
     */
    public function createClassImplementation(string $typeOfLayer, string $fileName) : bool;

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
    public function createFile(string $typeOfLayer, string $fileName, String $content, bool $isContract = false) : bool;

    /**
     * Creates folder based on given $fullPath.
     *
     * @param string $fullPath
     *
     * @return bool
     */
    public function createFolder(string $fullPath) : bool;

}