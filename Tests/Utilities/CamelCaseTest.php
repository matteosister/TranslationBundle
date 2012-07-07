<?php
/**
 * User: matteo
 * Date: 03/07/12
 * Time: 22.18
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Tests;

use Cypress\TranslationBundle\Tests\TestCase;
use Cypress\TranslationBundle\Utilities\CamelCase;

/**
 * Test for CamelCase
 */
class CamelCaseTest extends TestCase
{
    /**
     * @var CamelCase
     */
    private $camelCase;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        $this->camelCase = new CamelCase();
    }
    /**
     * fromCamelCase Tests
     */
    public function testFromCamelCase()
    {
        $this->assertEquals('test_test', $this->camelCase->fromCamelCase('TestTest'));
        $this->assertEquals('test', $this->camelCase->fromCamelCase('Test'));
        $this->assertEquals('test_t', $this->camelCase->fromCamelCase('TestT'));
    }

    /**
     * toCamelCase Tests
     */
    public function testToCamelCase()
    {
        $this->assertEquals('testTest', $this->camelCase->toCamelCase('test_test'));
        $this->assertEquals('TestTest', $this->camelCase->toCamelCase('test_test', true));
        $this->assertEquals('test', $this->camelCase->toCamelCase('test'));
        $this->assertEquals('Test', $this->camelCase->toCamelCase('test', true));
        $this->assertEquals('testT', $this->camelCase->toCamelCase('test_t'));
        $this->assertEquals('TestT', $this->camelCase->toCamelCase('test_t', true));
    }
}
