Install
-------

It's always the same

**Symfony 2.0.***

*/deps*
```
[CypressTranslationBundle]
    git=git://github.com/matteosister/TranslationBundle.git
    target=bundles/Cypress/TranslationBundle
```

*/app/autoload.php*
```php
<?php
$loader->registerNamespaces(array(
    // other namespaces
    'Cypress' => __DIR__.'/../vendor/bundles',
));
```

then

```sh
$ ./bin/vendors install
```

**Symfony 2.1.***

*composer.json*
```json
{
    "require": {
        "cypresslab-translation-bundle": "dev-master"
    }
}
```

Remember to add the minimum stability directive, because this bundle is still in alpha state

*composer.json (root)*
```json
{
    "minimum-stability": "dev"
}
```

then

```sh
$ curl -s http://getcomposer.org/installer | php
$ php composer.phar install
```

**symfony 2.0.* AND Symfony 2.1.***

*/app/AppKernel.php*
```php
<?php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // other bundles
            new Cypress\TranslationBundle\CypressTranslationBundle()
        );
    }
}
```