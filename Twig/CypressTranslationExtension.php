<?php
/**
 * User: matteo
 * Date: 03/07/12
 * Time: 23.46
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Twig;

class CypressTranslationExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

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
     * @param \Vivacom\CargoBundle\Entity\Abstracts\TranslatableEntity $ent   a TranslatableEntity
     * @param string                                                   $field name of the field
     *
     * @return mixed
     *
     * @throws \Twig_Error_Runtime
     */
    public function translateEntity($entity, $field, $lang = null) {
        if (!is_a($entity, 'Cypress\TranslationBundle\Entity\Base\TranslatableEntity')) {
            throw new \Twig_Error_Runtime('The "translate" filter can be applied only to an entity that extends "Cypress\TranslationBundle\Entity\Base\TranslatableEntity"');
        }
        if ($lang == null) {
            $lang = $this->container->get('request')->getLocale();
        }
        $method = 'get'.$this->container->get('cypress_translations_bundle.utilities.camel_case')->toCamelCase($field, true);
        if ($lang != $entity->getDefaultLanguage()) {
            $method .= $lang;
        }
        return $entity->$method();
    }

}