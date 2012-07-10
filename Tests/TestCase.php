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

use Cypress\TranslationBundle\Tests\Playground\Entity\Book,
    Cypress\TranslationBundle\Twig\CypressTranslationExtension;

class TestCase extends \PHPUnit_Framework_TestCase
{
    const TITLE_EN = 'the lord of the rings';
    const TITLE_ES = 'el señor de los anillos';
    const TITLE_IT = 'il signore degli anelli';

    /**
     * @var string
     */
    protected $dbFile;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    /**
     * @var string
     */
    private $twigLang;

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
        AnnotationRegistry::registerAutoloadNamespace("Doctrine\ORM", $doctrineDir . '/orm/lib');
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

    /**
     * get entity repository
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getBookRepo()
    {
        return $this->getEntityManager()->getRepository('Cypress\TranslationBundle\Tests\Playground\Entity\Book');
    }

    /**
     * @param array $criteria criteria
     *
     * @return Book
     */
    protected function getBook($criteria = array())
    {
        return $this->getBookRepo()->findOneBy($criteria);
    }

    /**
     * Create the schema
     */
    protected function createSchema()
    {
        $em = $this->getEntityManager();
        $tool = new SchemaTool($em);
        $classes = array(
            $em->getClassMetadata('Cypress\TranslationBundle\Tests\Playground\Entity\Book'),
            $em->getClassMetadata('Cypress\TranslationBundle\Tests\Playground\Entity\BookTranslations')
        );
        $tool->createSchema($classes);
    }

    /**
     * Delete the schema
     */
    protected function deleteSchema()
    {
        if (file_exists($this->dbFile)) {
            unlink($this->dbFile);
        }
    }

    /**
     * insert fixtures
     */
    protected function insertFakeData()
    {
        $book = new Book();
        $book->setTitle(static::TITLE_EN);
        $book->setTheTitle(static::TITLE_EN);
        $book->setTheCamelTitle(static::TITLE_EN);

        $book->setTitleEs(static::TITLE_ES);
        $book->setTheTitleEs(static::TITLE_ES);
        $book->setTheCamelTitleEs(static::TITLE_ES);

        $book->setTitleIt(static::TITLE_IT);
        $book->setTheTitleIt(static::TITLE_IT);
        $book->setTheCamelTitleIt(static::TITLE_IT);

        $this->getEntityManager()->persist($book);
        $this->getEntityManager()->flush();
    }

    protected function setupTwig($lang)
    {
        $this->twigLang = $lang;
        \Twig_Autoloader::register();

        $loader = new \Twig_Loader_Filesystem(__DIR__.'/Playground/tpl');
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => __DIR__.'/Playground/twig_cache',
            'debug' => true
        ));
        $mockContainer = $this->getMock('Container', array('has', 'get'));

        $mockContainer
            ->expects($this->exactly(2))
            ->method('get')
            ->with($this->logicalOr(
                $this->equalTo('request'),
                $this->equalTo('cypress_translations_bundle.utilities.camel_case')
            ))
            ->will($this->returnCallback(array($this, 'getService')));


        $this->twig->addExtension(new CypressTranslationExtension($mockContainer));
    }

    protected function getOutput($lang, $tpl = 'main.html.twig')
    {
        $this->setupTwig($lang);
        $book = $this->getBook();
        $template = $this->twig->loadTemplate($tpl);
        return $template->render(array('book' => $book));
    }

    public function getService($id)
    {
        if ('request' == $id) {
            $mockRequest = $this->getMock('Request', array('getLocale'));
            $mockRequest
                ->expects($this->once())
                ->method($this->equalTo('getLocale'))
                ->will($this->returnValue($this->twigLang));
            return $mockRequest;
        }
        if ('cypress_translations_bundle.utilities.camel_case' == $id) {
            return new \Cypress\TranslationBundle\Utilities\CamelCase();
        }
    }
}
