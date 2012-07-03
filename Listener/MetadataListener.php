<?php
/**
 * User: matteo
 * Date: 03/07/12
 * Time: 22.30
 *
 * Just for fun...
 */

namespace Cypress\TranslationBundle\Listener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

class MetadataListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetadata();
        $refl = new \ReflectionClass($metadata->getName());
        if ($refl->getParentClass() && $refl->getParentClass()->getName() == 'Cypress\TranslationBundle\Entity\Base\TranslatableEntity') {
            $name = $metadata->getName();
            $class = new $name();
            $fieldMapping = array(
                "fieldName"=> "translations",
                "type" => "int",
                "mappedBy" => "object",
                "targetEntity" => $class->getTranslationEntity(),
                "cascade" => array('persist', 'remove'),
                "orphanRemoval" => false,
                "fetch" => 2,
                "type" => 4,
                "inversedBy" => null,
                "isOwningSide" => false,
                "sourceEntity" => $metadata->getName(),
                "isCascadeRemove" => true,
                "isCascadePersist"=> true,
                "isCascadeRefresh" => false,
                "isCascadeMerge" => false,
                "isCascadeDetach" => false
            );
            $metadata->addInheritedAssociationMapping($fieldMapping);
        }
    }
}
