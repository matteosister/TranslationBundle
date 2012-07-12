TranslationBundle
=================

**Alpha state!** please report any bug you find! Or send a PR to become my personal hero...

A Symfony2 bundle for translating Doctrine2 entities ![Travis build status](https://secure.travis-ci.org/matteosister/TranslationBundle.png)

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

Configuration
-------------

Let's assume you have a **Book** entity with a title property

```php
<?php
namespace Cypress\MyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Books
 *
 * @ORM\Entity
 * @ORM\Table(name="book")
 */
class Book
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $title;

    // constructor, getter, setter and others amenities...
}
```

In order to translate it:

* create the BookTranslations class (pick the name you want), and make it extends the TranslationEntity superclass. You have to define the $object property, which has a ManyToOne relation with your main book class

```php
<?php
namespace Cypress\MyBundle\Entity;

use Cypress\TranslationBundle\Entity\Base\TranslationEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="book_translations",
 *     uniqueConstraints={@ORM\UniqueConstraint(name="lookup_unique_idx", columns={
 *         "locale", "object_id", "field"
 *     })}
 * )
 */
class BookTranslations extends TranslationEntity
{
    /**
     * @var Book
     *
     * @ORM\ManyToOne(targetEntity="Cypress\MyBundle\Entity\Book", inversedBy="translations")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    protected $object;
}

```

the sensible parts that you'll probably want to change is: the **namespace**, the **table name** and the **target entity**.

Do not change the inversedBy attribute! And, yes, this is your class, but do not add properties here, do it in the main class!

* add the **TranslatableEntity** superclass to your Book entity, and implement the three abstract methods

```php
<?php
namespace Cypress\MyBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Cypress\TranslationBundle\Entity\Base\TranslatableEntity;

/**
 * Books
 *
 * @ORM\Entity
 * @ORM\Table(name="book")
 */
class Book extends TranslatableEntity
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $title;

    // constructor, getter, setter and others amenities...

    /**
     * get the name of the TranslationEntity
     *
     * @return mixed
     */
    public function getTranslationEntity()
    {
        return 'Cypress\MyBundle\Entity\BookTranslations';
    }

    /**
     * get the default language
     *
     * @return string
     */
    public function getDefaultLanguage()
    {
        return 'en';
    }

    /**
     * get an array of supported languages
     *
     * @return array
     */
    public function getOtherLanguages()
    {
        return array('it', 'es');
    }
}
```

**getTranslationEntity**: return a string with the fully qualified name of the translation entity

**getDefaultLanguage**: return a string with the two digit code of the main language

**getOtherLanguages**: return an array with the two digit codes of the other languages

* Rebuild you model

```sh
$ ./app/console doctrine:schema:update --force
```

**Important**

If your translatable entity contains a constructor you HAVE to call the parent constructor. For example:

```php
<?php
class Book extends TranslatableEntity
{
    // properties

    public function __contruct()
    {
        // call the parent constructor
        parent::__construct();
        // your logic
        $this->authors = new ArrayCollection();
    }
}
```

**You're done!**

Usage
-----

**Now translate!!!!**

```php
<?php
use
$book = new Cypress\MyBundle\Entity\Book();
$book->setTitle('the lord of the rings'); // default language defined in getDefaultLanguage()
$book->setTitleEn('the lord of the rings'); // same as before
$book->setTitleEs('el señor de los anillos'); // set the title in spanish
$book->setTitleIt('il signore degli anelli'); // guess?
$book->setTitleRu('some weird letters here'); // throws an exception!

$em->persist($book); // $em is a doctrine entity manager
$em->flush(); // if you WTF on this go read the doctrine the docs... :)

// now retrieve
echo $book->getTitle(); // the lord of the rings
echo $book->getTitleEn(); // the lord of the rings
echo $book->getTitleIt(); // il signore degli anelli...
// and so on...
```

You can use any naming convention for your properties, underscore and camelCase, as long as you define a getter/setter for the prop.

Twig
----

In your twig templates you can use a nice filter

```html+jinja
{% for book in books %}
    <h1>{{ book|translate('title') }}</h1>
    <p>{{ book|translate('description') }}</p>
{% endfor %}
```

Remember to apply the filter directly to the TranslatableEntity instance, and to set the property name as the filter argument

If you don't use twig add this to your configuration file:

```yml
cypress_translation:
    twig: false
```

Testing
-------

This bundle is unit tested with phpunit. Here is the [travis build page](http://travis-ci.org/#!/matteosister/TranslationBundle)