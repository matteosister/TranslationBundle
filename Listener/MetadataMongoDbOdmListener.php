<?php
/**
 * User: matteo
 * Date: 03/07/12
 * Time: 22.30
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Listener;

use Doctrine\ODM\MongoDB\Event\LoadClassMetadataEventArgs;

/**
 * Listener for loadMetadataClass event
 */
class MetadataMongoDbOdmListener
{
    /**
     * register metadata for TranslatableEntity class
     *
     * @param \Doctrine\ORM\Event\LoadClassMetadataEventArgs $eventArgs args
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        $refl = new \ReflectionClass($metadata->getName());
        if ($refl->getParentClass() && $refl->getParentClass()->getName() == 'Cypress\TranslationBundle\Document\Base\TranslatableDocument') {
            $name = $metadata->getName();
            $class = new $name();
            $fieldMapping = array(
                "fieldName"=> "translations",
                "type" => "many",
                "embedded" => true,
                "targetDocument" => $class->getTranslationDocument(),
                "discriminatorField" => null,
                "discriminatorMap" => null,
                "strategy" => "pushAll",
                "name" => "tests",
                "nullable" => false,
                "options" => array(),
                "value" => null,
                "isCascadeRemove" => false,
                "isCascadePersist" => false,
                "isCascadeRefresh" => false,
                "isCascadeMerge" => false,
                "isCascadeDetach" => false,
                "isCascadeCallbacks" => false,
                "association" => 4,
                "isOwningSide" => true,
                "isInverseSide"=> false
            );
            //var_dump($metadata->getFieldMapping('translations'));
            $metadata->addInheritedFieldMapping($fieldMapping);
        }
    }
}
