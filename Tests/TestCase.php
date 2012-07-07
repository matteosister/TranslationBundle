<?php
/**
 * User: matteo
 * Date: 07/07/12
 * Time: 9.28
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Tests;

use Doctrine\ORM\EntityManager,
    Doctrine\ORM\Configuration,
    Doctrine\Common\Cache\ArrayCache,
    Doctrine\Common\ClassLoader,
    Doctrine\ORM\Tools\SchemaTool;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * Retrieve an EntityManager instance
     *
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        if (null !== $this->em) {
            return $this->em;
        }

        $doctrineDir = __DIR__.'/../../vendor/doctrine';
        $classLoader = new ClassLoader('Doctrine\Common', $doctrineDir . '/common/lib');
        $classLoader->register();

        $classLoader = new ClassLoader('Doctrine\DBAL', $doctrineDir . '/dbal/lib');
        $classLoader->register();

        $classLoader = new ClassLoader('Doctrine\ORM', $doctrineDir . '/orm/lib');
        $classLoader->register();

        $cache = new ArrayCache();
        $config = new Configuration;
        $config->setMetadataCacheImpl($cache);
        $driverImpl = $config->newDefaultAnnotationDriver(__DIR__.'/Playground/Entity');
        $config->setMetadataDriverImpl($driverImpl);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir(__DIR__.'/Playground/Proxies');
        $config->setProxyNamespace('Cypress\TranslationBundle\Tests\Playground\Proxies');

        $config->setAutoGenerateProxyClasses(true);

        $connectionOptions = array(
            'driver' => 'pdo_sqlite',
            'path' => __DIR__.'/Playground/db/test.db'
        );

        $this->em = EntityManager::create($connectionOptions, $config);
        return $this->em;
    }

    protected function createSchema()
    {
        $em = $this->getEntityManager();
        $tool = new SchemaTool($em);
        $classes = array(
          $em->getClassMetadata('Cypress\TranslationBundle\Tests\Playground\Entity\Book')
        );
        $tool->createSchema($classes);
    }
}
