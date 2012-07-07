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
        $this->createSchema();
    }

    public function testEntityTranslations()
    {
        $this->assertTrue(true);
    }
}
