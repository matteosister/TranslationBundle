<?php
/**
 * User: matteo
 * Date: 04/04/12
 * Time: 10.17
 *
 * Just for fun...
 */

namespace Vivacom\TranslationBundle\Entity\Base;

use Vivacom\CargoBundle\Entity\Abstracts\TraduzioneEntity;

abstract class TranslatableEntity
{
    /**
     * Aggiunge una traduzione, se già esiste aggiorna
     *
     * @param string $lang    locale
     * @param string $field   il campo da tradurre
     * @param string $content il contenuto del campo
     *
     * @abstract
     */
    abstract function addOrUpdateTranslation($lang, $field, $content);

    /**
     * setter della lingua dell'entità
     *
     * @param string $lang locale
     *
     * @abstract
     */
    abstract function setTranslatableLocale($lang);

    /**
     * getter della lingua
     *
     * @abstract
     * @return string
     */
    abstract function getLocale();

    /**
     * setter traduzioni
     *
     * @abstract
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $traduzioni collection di traduzioni
     */
    abstract function setTraduzioni($traduzioni);

    /**
     * getter traduzioni
     *
     * @abstract
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    abstract function getTraduzioni();

    /**
     * la proprietà di default da usare per le traduzioni
     *
     * @abstract
     * @return string
     */
    abstract function getToStringProperty();

    /**
     * magic method for get on translated fields
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (false !== $pos = strpos($name, '_en')) {
            $field = substr($name, 0, $pos);
            if (!property_exists($this, $field)) {
                throw new \InvalidArgumentException(sprintf('la proprietà %s non esiste', $field));
            }
            foreach ($this->getTraduzioni() as $traduzione) {
                if ($traduzione->getLocale() == 'en' && $traduzione->getField() == $field) {
                    return $traduzione->getContent();
                }
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
        if (false !== $pos = strpos($name, '_en')) {
            $field = substr($name, 0, $pos);
            $this->addOrUpdateTranslation('en', $field, $value);
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
            if (substr($name, strlen($name) - 2, strlen($name)) == 'En') {
                $property        = strtolower(str_replace('En', '', substr($name, 3))) . '_en';
                $this->$property = $arguments[0];
                return null;
            }
        } else {
            if ('get' === substr($name, 0, 3) && count($arguments) == 0) {
                $language = substr($name, strlen($name) - 2, strlen($name));
                if (in_array($language, array('It', 'En'))) {
                    // elimino get
                    $property = substr($name, 3);
                    // elimino la lingua (con substr, perchè potrebbe levare altre cose, occhio!)
                    $property = substr($property, 0, strlen($property) - 2);
                    // se diverso da It, aggiungo il suffisso della lingua
                    if ($language !== 'It') {
                        $property .= '_' . $language;
                    }
                    // la proprietà è minuscola sempre
                    $property = strtolower($property);
                    return $this->$property;
                }
            }
        }
        /* se arrivo qui viene lanciata eccezione */
        throw new \RuntimeException(sprintf('il metodo %s non esiste', $name));
    }

    /**
     * aggiunge una traduzione
     *
     * @param \Vivacom\CargoBundle\Entity\Abstracts\TraduzioneEntity $t
     */
    public function addTraduzione(TraduzioneEntity $t)
    {
        if (!$this->getTraduzioni()->contains($t)) {
            $traduzioni   = $this->getTraduzioni();
            $traduzioni[] = $t;
            $t->setObject($this);
        }
    }
}
