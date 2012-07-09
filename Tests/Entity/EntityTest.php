<?php
/**
 * User: matteo
 * Date: 07/07/12
 * Time: 9.28
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Tests\Entity;

use Cypress\TranslationBundle\Tests\TestCase;
use Cypress\TranslationBundle\Exception\RuntimeException;

class EntityTest extends TestCase
{
    public function setUp()
    {
        $this->deleteSchema();
        $this->createSchema();
        $this->insertFakeData();
    }

    public function testData()
    {
        $this->assertCount(1, $this->getBookRepo()->findAll());
    }

    public function testEntitygetters()
    {
        $book = $this->getBookRepo()->findOneBy(array());
        $this->assertEquals(static::TITLE_EN, $book->getTitle());
        $this->assertEquals(static::TITLE_IT, $book->getTitleIt());
        $this->assertEquals(static::TITLE_ES, $book->getTitleEs());
    }

    public function testEntitySetters()
    {
        $newTitleEn = 'new en';
        $newTitleEs = 'nuevo es';
        $newTitleIt = 'nuovo it';
        $book = $this->getBook();
        $book->setTitle($newTitleEn);
        $book->setTitleEs($newTitleEs);
        $book->setTitleIt($newTitleIt);
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
        $this->assertEquals($newTitleEn, $book->getTitle());
        $this->assertEquals($newTitleEs, $book->getTitleEs());
        $this->assertEquals($newTitleIt, $book->getTitleIt());
    }

    /**
     * @expectedException Cypress\TranslationBundle\Exception\RuntimeException
     */
    public function testDefaultLanguageException()
    {
        $this->assertEquals(static::TITLE_EN, $this->getBook()->getTitleEn());
    }

    public function testUnderscoreProperties()
    {
        $book = $this->getBookRepo()->findOneBy(array());
        $this->assertEquals(static::TITLE_EN, $book->getTheTitle());
        $this->assertEquals(static::TITLE_IT, $book->getTheTitleIt());
        $this->assertEquals(static::TITLE_ES, $book->getTheTitleEs());
    }

    public function testCamelCaseProperties()
    {
        $book = $this->getBookRepo()->findOneBy(array());
        $this->assertEquals(static::TITLE_EN, $book->getTheCamelTitle());
        $this->assertEquals(static::TITLE_IT, $book->getTheCamelTitleIt());
        $this->assertEquals(static::TITLE_ES, $book->getTheCamelTitleEs());
    }

    /**
     * @expectedException Cypress\TranslationBundle\Exception\RuntimeException
     */
    public function testInexistentPropertyExceptions()
    {
        $this->assertEquals(static::TITLE_IT, $this->getBook()->getMyTitle());
    }
}
