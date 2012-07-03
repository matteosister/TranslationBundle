<?php
/**
 * User: matteo
 * Date: 04/04/12
 * Time: 10.17
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Entity\Base;

use Cypress\TranslationBundle\Entity\Base\TranslationEntity;
use Doctrine\Common\Collections\ArrayCollection;

abstract class TranslatableEntity
{
    protected $locale;

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
     * get all the languages
     *
     * @return array
     */
    private function getAllLanguages()
    {
        return array_merge(array($this->getDefaultLanguage()), $this->getOtherLanguages());
    }

    /**
     * setter della lingua dell'entità
     *
     * @param string $lang locale
     *
     * @abstract
     */
    public function setTranslatableLocale($lang)
    {
        $this->locale = $lang;
    }

    /**
     * getter della lingua
     *
     * @abstract
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Add a translation, or update if already exists
     *
     * @param string $lang    locale
     * @param string $field   il campo da tradurre
     * @param string $content il contenuto del campo
     */
    public function addOrUpdateTranslation($lang, $field, $content) {
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
                throw new \RuntimeException(sprintf("You have defined the class '%' as a TranslationEntity, but it doesn't exists", $translationEntity));
            }
            $t = new $translationEntity($lang, $field, $content);
            $this->addTranslation($t);
        }
    }

    /**
     * magic method for get on translated fields
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        foreach ($this->getOtherLanguages() as $lang) {
            if ('_'.$lang === substr($name, strlen($name) - 3, 3)) {
                $field = substr($name, 0, strlen($name) - 3);
                if (!property_exists($this, $field)) {
                    throw new \InvalidArgumentException(sprintf('the property %s is not defined', $field));
                }
                foreach ($this->getTranslations() as $translation) {
                    if ($translation->getLocale() == $lang && $translation->getField() == $field) {
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
     * @param $name
     * @param $value
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
     * magic method per le chiamate set e get sui campi tradotti. i.e. setNomeEn("pippo")
     *
     * @param $name
     * @param $argument
     */
    public function __call($name, $arguments)
    {
        if ('set' === substr($name, 0, 3) && count($arguments) == 1) {
            foreach ($this->getOtherLanguages() as $lang) {
                if (substr($name, strlen($name) - 2, strlen($name)) == ucfirst($lang)) {
                    $propertyWithoutAction = substr($name, 3);
                    $property = $this->fromCamelCase(substr($propertyWithoutAction, 0, strlen($name) - 2));
                    $this->$property = $arguments[0];
                    return;
                }
            }
        } else {
            if ('get' === substr($name, 0, 3) && count($arguments) == 0) {
                $language = substr($name, strlen($name) - 2, strlen($name));
                if (in_array($language, $this->getAllLanguages())) {
                    // strip action
                    $property = substr($name, 3);
                    // elimino la lingua (con substr, perchè potrebbe levare altre cose, occhio!)
                    $property = $this->fromCamelCase(substr($property, 0, strlen($property) - 2));
                    // se diverso da It, aggiungo il suffisso della lingua
                    if ($language !== $this->getDefaultLanguage()) {
                        $property .= '_' . $language;
                    }
                    return $this->$property;
                }
            }
        }
        /* se arrivo qui viene lanciata eccezione */
        throw new \RuntimeException(sprintf('il metodo %s non esiste', $name));
    }

    /**
     * add a translation
     *
     * @param \Vivacom\CargoBundle\Entity\Abstracts\TranslationEntity $t
     */
    public function addTranslation(TranslationEntity $t)
    {
        if (!$this->getTranslations()->contains($t)) {
            $translations   = $this->getTranslations();
            $translations[] = $t;
            $t->setObject($this);
        }
    }

    /**
     * Traduzioni setter
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $traduzioni the traduzioni property
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;
    }

    /**
     * Traduzioni getter
     *
     * @return \Doctrine\Common\Collections\ArrayCollection
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
    private function toCamelCase($str, $capitaliseFirstChar = false)
    {
        if ($capitaliseFirstChar) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }
}
