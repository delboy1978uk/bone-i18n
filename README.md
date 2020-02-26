# i18n
I18n package for Bone Mvc Framework
## installation
Use Composer
```
composer require delboy1978uk/bone-i18n
```
## usage
Simply add to the `config/packages.php`
```php
<?php

// use statements here
use Bone\I18n\I18nPackage;

return [
    'packages' => [
        // packages here...,
        I18nPackage::class,
    ],
    // ...
];
```