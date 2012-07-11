<?php
/**
 * User: matteo
 * Date: 04/04/12
 * Time: 10.17
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Entity\Base;

use Cypress\TranslationBundle\Entity\Base\TranslationEntity,
    Cypress\TranslationBundle\Exception\RuntimeException;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * SuperClass for Translatable entities
 */
abstract class TranslatableEntity
{
    protected $translations;

    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * get the name of the TranslationEntity
     *
     * @abstract
     * @return mixed
     */
    abstract public function getTranslationEntity();

    /**
     * get the default language
     *
     * @abstract
     * @return string
     */
    abstract public function getDefaultLanguage();

    /**
     * get an array of supported languages
     *
     * @abstract
     * @return array
     */
    abstract public function getOtherLanguages();

    /**
     * get all the languages (default + others)
     *
     * @return array
     */
    private function getAllLanguages()
    {
        return array_merge(array($this->getDefaultLanguage()), $this->getOtherLanguages());
    }

    /**
     * Add a translation, or update if already exists
     *
     * @param string $lang    locale
     * @param string $field   field
     * @param string $content content
     *
     * @throws \Cypress\TranslationBundle\Exception\RuntimeException
     * @return void
     */
    public function addOrUpdateTranslation($lang, $field, $content)
    {
        if ($lang == $this->getDefaultLanguage()) {
            $this->$field = $content;
            return;
        }
        $update = false;
        foreach ($this->translations as $translation) {
            if ($lang == $translation->getLocale() && $field == $translation->getField()) {
                $translation->setContent($content);
                $update = true;
            }
        }
        if (!$update) {
            $translationEntity = $this->getTranslationEntity();
            if (!class_exists($translationEntity)) {
                throw new RuntimeException(sprintf("You have defined the class '%' as a TranslationEntity, but it doesn't exists", $translationEntity));
            }
            $t = new $translationEntity($lang, $field, $content);
            $this->addTranslation($t);
        }
    }

    /**
     * magic method for get on translated fields
     *
     * @param string $name property name
     *
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public function __get($name)
    {
        foreach ($this->getOtherLanguages() as $language) {
            if ('_'.$language === substr($name, strlen($name) - 3)) {
                $field = substr($name, 0, strlen($name) - 3);
                if (!property_exists($this, $field)) {
                    throw new \InvalidArgumentException(sprintf('the property %s is not defined', $field));
                }
                foreach ($this->getTranslations() as $translation) {
                    if ($translation->getLocale() == $language && $translation->getField() == $field) {
                        return $translation->getContent();
                    }
                }
                break;
            }
        }
    }

    /**
     * magic method per set su campi tradotti
     *
     * @param string $name  property name
     * @param mixed  $value value to set
     */
    public function __set($name, $value)
    {
        foreach ($this->getOtherLanguages() as $lang) {
            if ('_'.$lang === substr($name, strlen($name) - 3)) {
                $field = substr($name, 0, strlen($name) - 3);
                $this->addOrUpdateTranslation($lang, $field, $value);
            }
        }

    }

    /**
     * magic method for getters and setters on translated field
     * i.e.:
     *   getTitle() get title default language
     *   getTitleEn() get title in en
     *
     * @param string $name      method name
     * @param array  $arguments arguments array
     *
     * @throws \Cypress\TranslationBundle\Exception\RuntimeException
     * @return null|mixed
     */
    public function __call($name, $arguments)
    {
        if ('set' === substr($name, 0, 3) && count($arguments) == 1) {
            // SETTER
            if (strtolower(substr($name, strlen($name) - 2)) == $this->getDefaultLanguage()) {
                $method = substr($name, 0, strlen($name) - 2);
                call_user_func(array($this, $method), $arguments[0]);
                return;
            }
            foreach ($this->getOtherLanguages() as $language) {
                if (strtolower(substr($name, strlen($name) - 2)) == $language) {
                    $property = $this->methodToProperty($name).'_'.$language;
                    $this->$property = $arguments[0];
                    return null;
                }
            }
        } else {
            // GETTER
            if ('get' === substr($name, 0, 3) && count($arguments) == 0) {
                $language = strtolower(substr($name, strlen($name) - 2));
                $property = $this->methodToProperty($name);
                if ($language == $this->getDefaultLanguage()) {
                    return $this->getDefaultLanguageValue($name);
                } else if (in_array($language, $this->getAllLanguages())) {
                    // not the default language
                    if ($language !== $this->getDefaultLanguage()) {
                        $property .= '_'.$language;
                    }
                    return $this->$property;
                } else {
                    throw new RuntimeException(sprintf('You have request the translation in %s for the %s property, but the language is not defined as default nor as other language in the entity', $language, $property));
                }
            }
        }
        /* no method was found, throw exception */
        throw new RuntimeException(sprintf('the method %s doesn\'t exists', $name));
    }

    /**
     * add a translation
     *
     * @param \Cypress\TranslationBundle\Entity\Base\TranslationEntity $translation the translation to add
     */
    public function addTranslation(TranslationEntity $translation)
    {
        if (!$this->getTranslations()->contains($translation)) {
            $translations   = $this->getTranslations();
            $translations[] = $translation;
            $translation->setObject($this);
        }
    }

    /**
     * Translations setter
     *
     * @param ArrayCollection $translations the traduzioni property
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;
    }

    /**
     * Translations getter
     *
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Translates a camel case string into a string with underscores (e.g. firstName -&gt; first_name)
     *
     * @param string $str String in camel case format
     *
     * @return string Translated into underscore format
     */
    private function fromCamelCase($str)
    {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

    /**
     * Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
     *
     * @param string $str                 String in underscore format
     * @param bool   $capitaliseFirstChar If true, capitalise the first char in $str
     *
     * @return string translated into camel caps
     */
    private function toCamelCase($str, $capitaliseFirstChar = true)
    {
        if ($capitaliseFirstChar) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }

    /**
     * convert a getter/setter method to a property name
     *
     * @param string $method the method name
     *
     * @return string
     */
    private function methodToProperty($method)
    {
        // strip action
        $property = substr($method, 3);
        // strip language
        $propertyDirty = substr($property, 0, strlen($property) - 2);
        if ($this->hasProperty($this->fromCamelCase($propertyDirty))) {
            return $this->fromCamelCase($propertyDirty);
        } else if ($this->hasProperty(lcfirst($propertyDirty))) {
            return lcfirst($propertyDirty);
        } else {
            throw new RuntimeException(sprintf('there isn\'t a %s or %s property in the entity', $this->toCamelCase($propertyDirty), lcfirst($propertyDirty)));
        }
    }

    /**
     * get the default language value for the given property
     *
     * @param string $property property name
     *
     * @return mixed
     * @throws \Cypress\TranslationBundle\Exception\RuntimeException
     */
    private function getDefaultLanguageValue($method)
    {
        $method = substr($method, 0, strlen($method) - 2);
        $reflection = new \ReflectionClass($this);
        if (!$reflection->hasMethod($method)) {
            throw new RuntimeException(sprintf('You must implement a %s method', $method));
        }
        return call_user_func(array($this, $method));
    }

    /**
     * check if the entity has the property
     *
     * @param string $property property name
     *
     * @return bool
     */
    private function hasProperty($property)
    {
        $reflection = new \ReflectionClass($this);
        return $reflection->hasProperty($property);
    }
}
