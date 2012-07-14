<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matteo
 * Date: 04/07/12
 * Time: 22.50
 *
 * Just for fun...
 */

use Doctrine\Common\ClassLoader;

$composerAutoload = __DIR__.'/../vendor/autoload.php';

if (is_file($composerAutoload)) {
    include $composerAutoload;
} else {
    die('Unable to find autoload.php file, please use composer to load dependencies:

curl -s http://getcomposer.org/installer | php
php composer.phar install

Visit http://getcomposer.org/doc/01-basic-usage.md for more information.

');
}

spl_autoload_register(function($class)
{
    if (0 === strpos($class, 'Cypress\\TranslationBundle\\')) {
        $path = implode('/', array_slice(explode('\\', $class), 2)).'.php';
        require_once __DIR__.'/../'.$path;
        return true;
    }
});
