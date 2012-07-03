<?php
/**
 * User: matteo
 * Date: 04/04/12
 * Time: 10.49
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * Superclass for a translation entity
 */
abstract class TranslationEntity
{
    /**
     * @var integer $id
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    protected $id;

    /**
     * @var string $locale
     *
     * @ORM\Column(type="string", length=8)
     */
    protected $locale;

    /**
     * @var string $field
     *
     * @ORM\Column(type="string", length=32)
     */
    protected $field;

    /**
     * @var Object $object
     *
     * Related entity with ManyToOne relation
     * must be mapped by user
     */
    protected $object;

    /**
     * @var string $content
     *
     * @ORM\Column(type="text", nullable=true)
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

    /**
     * Object setter
     *
     * @param Object $object the object property
     */
    public function setObject($object)
    {
        $this->object = $object;
    }

    /**
     * Object getter
     *
     * @return Object
     */
    public function getObject()
    {
        return $this->object;
    }
}
