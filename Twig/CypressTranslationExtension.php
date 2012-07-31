<?php
/**
 * User: matteo
 * Date: 03/07/12
 * Time: 23.46
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Twig;

/**
 * Twig extension
 */
class CypressTranslationExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * Class constructor
     *
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container the service container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }
    /**
     * filtri
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
            'translate' => new \Twig_Filter_Method($this, 'translateEntity', array('is_safe' => array('html', 'json'))),
        );
    }

    /**
     * nome dell'estenzione
     *
     * @return string
     */
    public function getName()
    {
        return 'cypress_translation';
    }

    /**
     * Translate a field of an entity
     *
     * @param Object      $entity the entity
     * @param string      $field  name of the field
     * @param null|string $lang   the two digit language
     *
     * @throws \Twig_Error_Runtime
     * @return mixed
     *
     */
    public function translateEntity($entity, $field, $lang = null)
    {
        if (!is_a($entity, 'Cypress\TranslationBundle\Doctrine\Base\Translatable')) {
            throw new \Twig_Error_Runtime('The "translate" filter can be applied only to an entity that extends "Cypress\TranslationBundle\Doctrine\Base\Translatable"');
        }
        // sf 2.1
        if ($lang == null) {
            $request = $this->container->get('request');
            if (method_exists($request, 'getLocale')) {
                $lang = $this->container->get('request')->getLocale();
            }
            // sf 2.0
            if ($lang == null) {
                $session = $this->container->get('session');
                if (method_exists($session, 'getLocale')) {
                    $lang = $this->container->get('session')->getLocale();
                }
            }
        }
        if (is_array($field)) {
            $methods = array();
            foreach ($field as $oneField) {
                $method = $this->generateMethodName($oneField, $lang, $entity);
                $methods[] = $method;
                try {
                    return $entity->$method();
                } catch (\Exception $e) {
                }
            }
            throw new \RuntimeException(sprintf('Trying to call one of the methods %s on the entity %s, but none of these methods seems to exists. Maybe a mispelling?', '"'.implode('", "', $methods).'"', get_class($entity)));
        }

        $method = $this->generateMethodName($field, $lang, $entity);
        return $entity->$method();
    }

    private function generateMethodName($field, $lang, $entity)
    {
        $method = 'get'.$this->container->get('cypress_translations_bundle.utilities.camel_case')->toCamelCase($field, true);
        if ($lang != $entity->getDefaultLanguage()) {
            $method .= $lang;
        }
        return $method;
    }


}