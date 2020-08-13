<?php
declare(strict_types=1);

namespace Ritenn\Implementator\Services;


use Ritenn\Implementator\Contracts\BindingContract;
use Ritenn\Implementator\ImplementatorBase;

/**
 * Class BindingService
 * @package Ritenn\Implementator\Services
 *
 * Automatically binding Contracts/Interfaces to Service/Repository implementations.
 */
class BindingService extends ImplementatorBase implements BindingContract {

    /**
     * Cached data (if cache is enabled in config)
     *
     * @var array|\Illuminate\Cache\CacheManager|\Illuminate\Contracts\Foundation\Application|mixed
     */
    public $cachedBindings;

    public function __construct()
    {
        parent::__construct();

        $this->cachedBindings = cache('ImplementatorBindings') ?? array();
    }

    /**
     * Dynamically recursively bind interfaces to implementations
     *
     * @return void
     */
    public function register() : void
    {
        $layers = ['Services', 'Repositories'];

        /**
         * Iterates through your layers and implements contracts/interfaces
         */
        foreach ($layers as $layer)
        {
            if ($this->checkIfFolderExists($this->baseAppPath . '/' . $layer))
            {
                $this->runDirectoryIterator($layer);
            }
        }
    }

    /**
     * @param string $layerName
     * @throws \Exception
     */
    private function runDirectoryIterator(string $layerName) : void
    {
        $path = $this->getFolderPath(false, $layerName);

        $directoryIterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($directoryIterator as $item) {

            if ($item->isFile()) {

                $extension = '.' . $item->getExtension();
                $filename = $item->getBasename($extension);

                $this->bindInterface($layerName, $filename);
            }
        }
    }

    /**
     * @param string $layerName
     * @param string $filename
     *
     * @throws \Exception
     */
    private function bindInterface(string $layerName, string $filename) : void
    {
        $fileNameBase = $this->getFilenameBase($filename, $layerName);

        if
        (
            $this->checkIfFileExists($this->getImplementationFileFullPath($layerName, $fileNameBase)) &&
            $this->checkIfFileExists($this->getContractFileFullPath($layerName, $fileNameBase))
        ) {

            $interface = $this->getContractNamespaceByLayerClassName($filename);
            $implementation = $this->getLayerFullNamespaceByClassName($filename);

            app()->bind($interface, $implementation);
            $this->addBindingToCachedArray($interface, $implementation);

        } else {

            throw new \Exception("Interface or implementation filename is wrong or doesn't exists: " . $filename);

        }
    }

    /**
     * Adds binding to cached array, if cache is enabled in config/implementator
     *
     * @param string $interface
     * @param string $implementation
     *
     * @return bool
     * @throws \Exception
     */
    public function addBindingToCachedArray(string $interface, string $implementation) : bool
    {
        if (config('implementator.cache'))
        {
            $bindingsCache = cache('ImplementatorBindings');
            $bindings = is_array($bindingsCache) ? $bindingsCache : array();

            array_push($bindings, [$interface, $implementation]);
            return (bool) cache()->forever('ImplementatorBindings', $bindings);
        }

        return false;
    }

    /**
     * Resets binding cache
     *
     * @return bool
     * @throws \Exception
     */
    public function resetCache() : bool
    {
        return cache()->forget('ImplementatorBindings');
    }

    /**
     * Checks config settings and if there is already anythinng in cached array
     *
     * @return bool
     */
    public function canLoadFromCache() : bool
    {
        return config('implementator.cache') && is_array($this->cachedBindings) && !empty($this->cachedBindings);
    }

}