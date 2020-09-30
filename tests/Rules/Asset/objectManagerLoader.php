<?php

use Doctrine\Common\Persistence\ObjectManager;

return new class() implements ObjectManager {
    public function getClassMetadata($className)
    {
        if ($className === \Otobank\PHPStan\Doctrine\Rules\Asset\AcmeEntity::class) {
            return new class() implements \Doctrine\Persistence\Mapping\ClassMetadata {
                public $fieldMappings = [
                    'foo' => [],
                    'bar' => [],
                    'embedded.baz' => [],
                ];
                public $embeddedClasses = [
                    'embedded' => [
                        'class' => \Otobank\PHPStan\Doctrine\Rules\Asset\EmbeddedEntity::class,
                        'columnPrefix' => false,
                        'declaredField' => null,
                        'originalField' => null,
                    ],
                ];

                public function hasField($fieldName)
                {
                    return isset($this->fieldMappings[$fieldName]) || isset($this->embeddedClasses[$fieldName]);
                }

                public function getAssociationTargetClass($assocName)
                {
                    // TODO: Implement getAssociationTargetClass() method.
                }

                public function getName()
                {
                    // TODO: Implement getName() method.
                }

                public function getIdentifier()
                {
                    // TODO: Implement getIdentifier() method.
                }

                public function getReflectionClass()
                {
                    // TODO: Implement getReflectionClass() method.
                }

                public function isIdentifier($fieldName)
                {
                    // TODO: Implement isIdentifier() method.
                }

                public function hasAssociation($fieldName)
                {
                    // TODO: Implement hasAssociation() method.
                }

                public function isSingleValuedAssociation($fieldName)
                {
                    // TODO: Implement isSingleValuedAssociation() method.
                }

                public function isCollectionValuedAssociation($fieldName)
                {
                    // TODO: Implement isCollectionValuedAssociation() method.
                }

                public function getFieldNames()
                {
                    // TODO: Implement getFieldNames() method.
                }

                public function getIdentifierFieldNames()
                {
                    // TODO: Implement getIdentifierFieldNames() method.
                }

                public function getAssociationNames()
                {
                    // TODO: Implement getAssociationNames() method.
                }

                public function getTypeOfField($fieldName)
                {
                    // TODO: Implement getTypeOfField() method.
                }

                public function isAssociationInverseSide($assocName)
                {
                    // TODO: Implement isAssociationInverseSide() method.
                }

                public function getAssociationMappedByTargetField($assocName)
                {
                    // TODO: Implement getAssociationMappedByTargetField() method.
                }

                public function getIdentifierValues($object)
                {
                    // TODO: Implement getIdentifierValues() method.
                }
            };
        }
    }

    public function find($className, $id)
    {
        // TODO: Implement find() method.
    }

    public function persist($object)
    {
        // TODO: Implement persist() method.
    }

    public function remove($object)
    {
        // TODO: Implement remove() method.
    }

    public function merge($object)
    {
        // TODO: Implement merge() method.
    }

    public function clear($objectName = null)
    {
        // TODO: Implement clear() method.
    }

    public function detach($object)
    {
        // TODO: Implement detach() method.
    }

    public function refresh($object)
    {
        // TODO: Implement refresh() method.
    }

    public function flush()
    {
        // TODO: Implement flush() method.
    }

    public function getRepository($className)
    {
        // TODO: Implement getRepository() method.
    }

    public function getMetadataFactory()
    {
        // TODO: Implement getMetadataFactory() method.
    }

    public function initializeObject($obj)
    {
        // TODO: Implement initializeObject() method.
    }

    public function contains($object)
    {
        // TODO: Implement contains() method.
    }
};
