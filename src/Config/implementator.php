<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Terminology
    |--------------------------------------------------------------------------
    |
    | This value sets name for your interfaces folder as "Contracts"
    | but you can also rename it to "Interfaces", if you want.
    */
    'terminology' => 'Contracts',
    /*
    |--------------------------------------------------------------------------
    | Contracts sub folder categories
    |--------------------------------------------------------------------------
    |
    | By default your Contracts are grouped in folders by type
    | (Repository/Service). If you want to keep everything in one folder set
    | this value to false.
    |
    | Set this value before creating any classes.
    */
    'contracts_categories' => true,
    /*
    |--------------------------------------------------------------------------
    | Enable cache, default: true
    |--------------------------------------------------------------------------
    |
    | By using cache the performance is like you set it manually in provider.
    | It's recommended to enable in production
    */
    'cache' => true,
    /*
    |--------------------------------------------------------------------------
    | Binding exceptions
    |--------------------------------------------------------------------------
    |
    | If your implementations contains some additional parameters that won't be
    | auto-resolved by Laravel, you can exclude them from auto-binding and bind
    | them on your own.
    |
    | Pass contract and implementation as array e.g.
    | array(['App\Contracts\Services\TestContract' => 'App\Services\TestService'], [... => ...], ...)
    */
    'binding_exceptions' => array()

];