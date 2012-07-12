MongoDB Integration
-------------------

The solution is almost the same...but you have to extend the document base classes, and define by hand the translations property.

Here is an example of a translated document

```php
<?php
namespace Cypress\MyBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Cypress\TranslationBundle\Document\Base\TranslatableDocument;

/**
 * Page document
 *
 * @MongoDB\Document
 */
class Page extends TranslatableDocument
{
    /**
     * @var int
     *
     * @MongoDB\Id
     */
    private $id;

    /**
     * @var string
     *
     * @MongoDB\String
     */
    private $title;

    /**
     * @var ArrayCollection
     *
     * @MongoDB\EmbedMany(targetDocument="Vivacom\CmsBundle\Document\PageTranslations")
     */
    protected $translations;

    /**
     * Class constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    // here, normal setters/getters for the document properties

    /**
     * get the name of the TranslationDocument
     *
     * @return mixed
     */
    public function getTranslationDocument()
    {
        return 'Cypress\MyBundle\Document\PageTranslations';
    }

    /**
     * get the default language
     *
     * @return string
     */
    public function getDefaultLanguage()
    {
        return 'it';
    }

    /**
     * get an array of supported languages
     *
     * @return array
     */
    public function getOtherLanguages()
    {
        return array('en', 'es');
    }
}
```

**Some important things to notice**

* you have to manually define the *translations* property, and **define it as protected** as the base class needs access
* you have to call the **parent constructor** if you define one


Here is the translation document

```php
<?php
namespace Vivacom\CmsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Cypress\TranslationBundle\Document\Base\TranslationDocument;

/**
 * @MongoDB\EmbeddedDocument
 */
class PageTranslations extends TranslationDocument
{
}
```

that simple!

Embedded vs Referenced
----------------------

I think that entity translation are perfect for an embedded document. But if you want a translation collection it's easy as change EmebedMany with ReferenceMany, and add the cascade on the base class

```php
<?php
namespace Cypress\MyBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\Common\Collections\ArrayCollection;
use Cypress\TranslationBundle\Document\Base\TranslatableDocument;

/**
 * Page document
 *
 * @MongoDB\Document
 */
class Page extends TranslatableDocument
{
    /**
     * @var ArrayCollection
     *
     * @MongoDB\ReferenceMany(targetDocument="Vivacom\CmsBundle\Document\PageTranslations", cascade={"all"})
     */
    protected $translations;
}
```

and define the translation document as a real one, not an embedded one

```php
<?php
namespace Vivacom\CmsBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Cypress\TranslationBundle\Document\Base\TranslationDocument;

/**
 * @MongoDB\Document
 */
class PageTranslations extends TranslationDocument
{
}
```