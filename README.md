# i18n
I18n package for Bone Mvc Framework
## installation
Use Composer
```
composer require delboy1978uk/bone-i18n
```
## setup
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
Create directory for translations (usually `data/translations`), which will contain locale folders such as `en_US` etc.
Drop in your `.mo` and `.po` files. Add the following config file and tweak to suit:
```php
<?php

use Laminas\I18n\Translator\Loader\Gettext;

return [
    'i18n' => [
        'enabled' => true,
        'translations_dir' => 'data/translations',
        'type' => Gettext::class,
        'default_locale' => 'en_PI',
        'supported_locales' => ['en_PI', 'en_GB', 'nl_BE', 'fr_BE'],
        'date_format' => 'd/m/Y',
    ]
];
```
For any package you have that will contain translation files, edit your package class and make it implement
`Bone\I18n\I18nRegistrationInterface`. Create the method `public function getTranslationsDirectory(): string` that will 
return the translations directory path.
## usage
#### locale view helper
You can have routes prepended with the current locale by calling 
```php
<?= $this->locale() ?>
``` 
Or more conveniently
```php
<?= $this->l() ?>
``` 
in your view files.
A link such as `/user` would then become `/en_US/user`. Bone Framework uses i18n middleware to fetch the locale and it
strips the locale from the URL and sets it as a Request Attribute, so you do not need to define routes with a locale 
parameter. 
#### controllers
In a controller action if you need the locale you can say:
```php
$locale = $request->getAttribute('locale');
```
To get a translator into your controller, make it implement `Bone\I18n\I18nAwareInterface` and use the 
`Bone\Traits\HasTranslatorTrait`. If you package's Package class returns the controller without going through the 
`Bone\Mvc\Controller\Init` class, change it now to this:
```php
return  Init::controller(new YourController(), $c);
```
You can now call `$this->getTranslator()` which will return an instance of the translator.
#### translation view helper
To translate text in your view, call the following:
```php
<?= $this->translate('whatever.key.to.translate') ;?>
```
Or again, more conveniently
```php
<?= $this->t('whatever.key.to.translate') ;?>
```
#### i18n aware forms
Bone Framework uses `delboy1978uk/form` for its form functionality. However, instead of extending `Del\Form`, you can
create a form extending `Bone\I18n\Form`, which takes the translator as a second argument.  