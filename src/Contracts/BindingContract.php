<?php

namespace Ritenn\Implementator\Contracts;


interface BindingContract
{
    /**
     * Dynamically recursively bind interfaces to implementations
     */
    public function register(): void;

    /**
     * Adds binding to cached array, if cache is enabled in config/implementator
     *
     * @param string $interface
     * @param string $implementation
     */
    public function addBindingToCachedArray(string $interface, string $implementation): bool;

    /**
     * Resets binding cache
     */
    public function resetCache(): bool;

    /**
     * Checks config settings and if there is already anythinng in cached array
     */
    public function canLoadFromCache(): bool;
}