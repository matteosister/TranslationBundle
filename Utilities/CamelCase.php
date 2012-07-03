<?php
/**
 * User: matteo
 * Date: 03/07/12
 * Time: 23.57
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Utilities;

/**
 * Utilities class to manage camelCase to under_score conversions
 */
class CamelCase
{
    /**
     * Translates a camel case string into a string with underscores (e.g. firstName -&gt; first_name)
     *
     * @param string $str String in camel case format
     *
     * @return string Translated into underscore format
     */
    public function fromCamelCase($str)
    {
        $str[0] = strtolower($str[0]);
        $func = create_function('$c', 'return "_" . strtolower($c[1]);');
        return preg_replace_callback('/([A-Z])/', $func, $str);
    }

    /**
     * Translates a string with underscores into camel case (e.g. first_name -&gt; firstName)
     *
     * @param string $str                 String in underscore format
     * @param bool   $capitaliseFirstChar If true, capitalise the first char in $str
     *
     * @return string translated into camel caps
     */
    public function toCamelCase($str, $capitaliseFirstChar = false)
    {
        if ($capitaliseFirstChar) {
            $str[0] = strtoupper($str[0]);
        }
        $func = create_function('$c', 'return strtoupper($c[1]);');
        return preg_replace_callback('/_([a-z])/', $func, $str);
    }
}
