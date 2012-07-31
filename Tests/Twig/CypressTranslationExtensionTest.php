<?php
/**
 * User: matteo
 * Date: 10/07/12
 * Time: 15.54
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Tests\Twig;

use Cypress\TranslationBundle\Tests\TestCase;

class CypressTranslationExtensionTest extends TestCase
{
    public function setUp()
    {
        $this->deleteSchema();
        $this->createSchema();
        $this->insertFakeData();
    }

    public function testExtension()
    {
        $this->assertEquals(static::TITLE_EN, $this->getOutput('en'));
        $this->assertEquals(static::TITLE_IT, $this->getOutput('it'));
        $this->assertEquals(static::TITLE_ES, $this->getOutput('es'));
        $this->assertEquals(static::TITLE_ES, $this->getOutput('en', 'main.html.twig', 'es'));
        $this->assertEquals(static::TITLE_IT, $this->getOutput('en', 'main.html.twig', 'it'));
        $this->assertEquals(static::TITLE_EN, $this->getOutput('en', 'main.html.twig', 'en'));

        $this->assertEquals(static::TITLE_EN, $this->getOutput('en', 'underscore.html.twig'));
        $this->assertEquals(static::TITLE_IT, $this->getOutput('it', 'underscore.html.twig'));
        $this->assertEquals(static::TITLE_ES, $this->getOutput('es', 'underscore.html.twig'));
        $this->assertEquals(static::TITLE_ES, $this->getOutput('en', 'underscore.html.twig', 'es'));
        $this->assertEquals(static::TITLE_IT, $this->getOutput('en', 'underscore.html.twig', 'it'));
        $this->assertEquals(static::TITLE_EN, $this->getOutput('en', 'underscore.html.twig', 'en'));

        $this->assertEquals(static::TITLE_EN, $this->getOutput('en', 'camelCase.html.twig'));
        $this->assertEquals(static::TITLE_IT, $this->getOutput('it', 'camelCase.html.twig'));
        $this->assertEquals(static::TITLE_ES, $this->getOutput('es', 'camelCase.html.twig'));
        $this->assertEquals(static::TITLE_ES, $this->getOutput('en', 'camelCase.html.twig', 'es'));
        $this->assertEquals(static::TITLE_IT, $this->getOutput('en', 'camelCase.html.twig', 'it'));
        $this->assertEquals(static::TITLE_EN, $this->getOutput('en', 'camelCase.html.twig', 'en'));

        $this->assertEquals(static::AUTHOR_EN, $this->getOutput('en', 'multiple_property_name.html.twig'));
        $this->assertEquals(static::AUTHOR_ES, $this->getOutput('es', 'multiple_property_name.html.twig'));
        $this->assertEquals(static::AUTHOR_IT, $this->getOutput('it', 'multiple_property_name.html.twig'));
    }
}
