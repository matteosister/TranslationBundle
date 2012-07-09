<?php
/**
 * User: matteo
 * Date: 07/07/12
 * Time: 9.31
 * 
 * Just for fun...
 */
 
namespace Cypress\TranslationBundle\Tests\Playground\Entity;

use Cypress\TranslationBundle\Entity\Base\TranslatableEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Book table for testing purpose
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

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $the_title;

    /**
     * @var string
     *
     * @ORM\Column
     */
    private $theCamelTitle;

    /**
     * get the name of the TranslationEntity
     *
     * @return mixed
     */
    public function getTranslationEntity()
    {
        return 'Cypress\TranslationBundle\Tests\Playground\Entity\BookTranslations';
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
     * Title setter
     *
     * @param string $title the title property
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Title getter
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * TheTitle setter
     *
     * @param string $the_title the the_title property
     */
    public function setTheTitle($the_title)
    {
        $this->the_title = $the_title;
    }

    /**
     * TheTitle getter
     *
     * @return string
     */
    public function getTheTitle()
    {
        return $this->the_title;
    }

    /**
     * TheCamelTitle setter
     *
     * @param string $theCamelTitle the theCamelTitle property
     */
    public function setTheCamelTitle($theCamelTitle)
    {
        $this->theCamelTitle = $theCamelTitle;
    }

    /**
     * TheCamelTitle getter
     *
     * @return string
     */
    public function getTheCamelTitle()
    {
        return $this->theCamelTitle;
    }
}
