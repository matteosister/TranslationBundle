<?php
/**
 * User: matteo
 * Date: 04/04/12
 * Time: 10.49
 *
 * Just for fun...
 */

namespace Vivacom\TranslationBundle\Entity\Base;

use Gedmo\Translatable\Entity\MappedSuperclass\AbstractPersonalTranslation;

abstract class TraduzioneEntity extends AbstractPersonalTranslation
{
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
}
