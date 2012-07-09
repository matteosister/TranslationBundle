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

class EntityTest extends TestCase
{
    public function setUp()
    {
        $this->deleteSchema();
        $this->createSchema();
        $this->insertFakeData();
    }

    public function testEntitygetters()
    {
        $this->assertCount(1, $this->getBookRepo()->findAll());

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
        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
        $this->assertEquals($newTitleEn, $book->getTitle());
    }
}
