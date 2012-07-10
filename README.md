TranslationBundle
=================

A Symfony2 bundle for translating Doctrine2 entities


Install
-------

It's always the same

On Symfony 2.0.*

*/deps*
```
[CypressTranslationBundle]
    git=git://github.com/matteosister/TranslationBundle.git
    target=bundles/Cypress/TranslationBundle
```

*/app/autoload.php*
```php
$loader->registerNamespaces(array(
    // other namespaces
    'Cypress' => __DIR__.'/../vendor/bundles',
));
```

then

```sh
# ./bin/vendors install
```