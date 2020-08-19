## Intro
This package adds additional 3 artisan commands that will speed up your development. You can quickly create contract/interface and implement it to your service or repository layer just like with any other artisan commands e.g. ```make:model``` etc. Additionally, it automatically binds interface to implementation by base file name, which means that you don't have to waste time to do it manually.

Make your code clean and readable!

## Installation

Requires PHP >=7.0 and Laravel 7.x

```composer require ritenn/implementator```

## Publishing config

That will copy 'implementator' config to your app config directory
```php
php artisan vendor:publish --provider="Ritenn\Implementator\ImplementatorServiceProvider" --force
```

## Available commands and options

- ```php artisan make:service *baseName``` - creates contract and implementation for your service layer
- ```php artisan make:repository *baseName``` - creates contract and implementation for your repository
- You can also use option ```--without-contract```, if you want to create just layer without contract implementation
- Finally, you can create contract without implementation using ```php artisan make:contract *baseName```. However, if contracts categorization is enabled in your config you must also specify layer name e.g. ```--layer=Services```

<sub>*baseName - e.g. TestService, base name is 'Test', UserRepository > User, CartContract > Cart<sup>
## Configuration
1) You can change it to 'Interfaces' if you wish, that will affect folders/files/class names<br/>
```'terminology' => 'Contracts'```<br/>
2) If enabled, commands will create additional subfolders for your contracts<br/>
```'contracts_categories' => true```<br/>
3) If enabled, bindings will be loaded from cache in production<br/>
```'cache' => true```<br/>
4) You can add exceptions for auto-bindings, if your class requires additional parameters that won't be auto resolved by laravel.<br/> 
```'binding_exceptions' => array()```<br/>
e.g.<br/>
```binding_exceptions' => array(['App\Contracts\Services\TestContract' => 'App\Services\TestService'], [... => ...], ...)```