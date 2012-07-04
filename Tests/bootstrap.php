<?php
/**
 * Created by JetBrains PhpStorm.
 * User: matteo
 * Date: 04/07/12
 * Time: 22.50
 *
 * Just for fun...
 */
 
require_once $_SERVER['SYMFONY'].'/Symfony/Component/ClassLoader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();
$loader->registerNamespace('Symfony', $_SERVER['SYMFONY']);
$loader->register();


spl_autoload_register(function($class)
{
    if (0 === strpos($class, 'Cypress\\TranslationBundle\\')) {
        $path = implode('/', array_slice(explode('\\', $class), 2)).'.php';
        require_once __DIR__.'/../'.$path;
        return true;
    }
});