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
        $this->assertCount(1, $this->getAuthorRepo()->findAll());
    }

    public function testPropertiesAccess()
    {
        $book = $this->getBook();
        $this->assertEquals(static::TITLE_EN, $book->title);
        $this->assertEquals(static::TITLE_EN, $book->title_en);
        $this->assertEquals(static::TITLE_ES, $book->title_es);
        $this->assertEquals(static::TITLE_IT, $book->title_it);
        $newTitleEn = 'new en';
        $newTitleEs = 'nuevo es';
        $newTitleIt = 'nuovo it';
        $book->title = $newTitleEn;
        $book->title_es = $newTitleEs;
        $book->title_it = $newTitleIt;
        $this->assertEquals($newTitleEn, $book->title);
        $this->assertEquals($newTitleEn, $book->title_en);
        $this->assertEquals($newTitleEs, $book->title_es);
        $this->assertEquals($newTitleIt, $book->title_it);
    }

    public function testEntitygetters()
    {
        $book = $this->getBook();
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

    public function testSetterDefaultLanguage()
    {
        $newTitleEn = 'new en';
        $book = $this->getBook();
        $book->setTitleEn($newTitleEn);
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
        $this->assertEquals($newTitleEn, $book->getTitle());
    }

    public function testDefaultLanguage()
    {
        $this->assertEquals(static::TITLE_EN, $this->getBook()->getTitleEn());
        $this->assertEquals(static::TITLE_EN, $this->getBook()->getTheTitleEn());
        $this->assertEquals(static::TITLE_EN, $this->getBook()->getTheCamelTitleEn());
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

    /**
     * calling a non-existent property should raise an error
     *
     * @expectedException Cypress\TranslationBundle\Exception\RuntimeException
     */
    public function testErrorRaised()
    {
        $book = $this->getBook();
        $book->test;
        $book->test_it;
        $book->test = 'test';
    }
}
