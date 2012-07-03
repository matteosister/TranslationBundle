<?php
/**
 * User: matteo
 * Date: 03/07/12
 * Time: 22.18
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Tests;

use Cypress\TranslationBundle\Utilities\CamelCase;

/**
 * Test for CamelCase
 */
class TranslatableEntityTest extends \PHPUnit_Framework_TestCase
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
        $this->doAssert('TestTest', 'test_test', 'fromCamelCase');
        $this->doAssert('Test', 'test', 'fromCamelCase');
    }

    /**
     * dummy method to test
     *
     * @param string $test     test
     * @param string $expected expected result
     * @param string $method   method name
     */
    private function doAssert($test, $expected, $method)
    {
        $this->assertEquals($expected, $this->camelCase->$method($test));
    }
}
