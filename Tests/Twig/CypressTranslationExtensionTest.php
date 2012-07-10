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
        $this->setupTwig();
        $book = $this->getBook();
        $template = $this->twig->loadTemplate('main.html.twig');
        $template->render(array('book' => $book));
    }
}
