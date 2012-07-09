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
    Doctrine\ORM\Tools\SchemaTool,
    Doctrine\Common\Annotations\AnnotationRegistry,
    Doctrine\ORM\Mapping\Driver\AnnotationDriver,
    Doctrine\Common\Annotations\CachedReader,
    Doctrine\Common\Annotations\AnnotationReader;

class TestCase extends \PHPUnit_Framework_TestCase
{
    protected $dbFile;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function __construct()
    {
        parent::__construct();
        $this->dbFile = __DIR__.'/Playground/db/test.db';
    }

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

        $doctrineDir = realpath(__DIR__.'/../vendor/doctrine');
        $classLoader = new ClassLoader('Doctrine\Common', $doctrineDir . '/common/lib');
        $classLoader->register();

        $classLoader = new ClassLoader('Doctrine\DBAL', $doctrineDir . '/dbal/lib');
        $classLoader->register();

        $classLoader = new ClassLoader('Doctrine\ORM', $doctrineDir . '/orm/lib');
        $classLoader->register();

        $cache = new ArrayCache();
        $config = new Configuration;
        $config->setMetadataDriverImpl(
            new AnnotationDriver(
                new CachedReader(
                    new AnnotationReader(),
                    $cache
                ),
                array(__DIR__.'/Playground/Entity')
            )
        );
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);
        $config->setProxyDir(__DIR__.'/Playground/Proxies');
        $config->setProxyNamespace('Cypress\TranslationBundle\Tests\Playground\Proxies');

        $config->setAutoGenerateProxyClasses(true);

        $connectionOptions = array(
            'driver' => 'pdo_sqlite',
            'path' => $this->dbFile
        );

        $this->em = EntityManager::create($connectionOptions, $config);
        return $this->em;
    }

    protected function createSchema()
    {
        $em = $this->getEntityManager();
        $tool = new SchemaTool($em);
        $classes = array(
            $em->getClassMetadata('Cypress\TranslationBundle\Tests\Playground\Entity\Book'),
            //$em->getClassMetadata('Cypress\TranslationBundle\Tests\Playground\Entity\BookTranslations')
        );
        $tool->createSchema($classes);
    }

    protected function deleteSchema()
    {
        if (file_exists($this->dbFile)) {
            unlink($this->dbFile);
        }
    }
}
