<?php
/**
 * User: matteo
 * Date: 04/04/12
 * Time: 10.49
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Document\Base;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * Superclass for a translation entity
 */
abstract class TranslationDocument
{
    /**
     * @var integer $id
     *
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @var string $locale
     *
     * @MongoDB\String
     */
    protected $locale;

    /**
     * @var string $field
     *
     * @MongoDB\String
     */
    protected $field;

    /**
     * @var string $content
     *
     * @MongoDB\String
     */
    protected $content;

    /**
     * Constructor
     *
     * @param string $locale the locale
     * @param string $field  the field to be translated
     * @param string $value  the field value
     */
    final public function __construct($locale, $field, $value)
    {
        $this->setLocale($locale);
        $this->setField($field);
        $this->setContent($value);
    }

    /**
     * Id getter
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Content setter
     *
     * @param string $content the content property
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Content getter
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Field setter
     *
     * @param string $field the field property
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * Field getter
     *
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * Locale setter
     *
     * @param string $locale the locale property
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    /**
     * Locale getter
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }
}
