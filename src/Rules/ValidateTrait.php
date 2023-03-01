<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use Doctrine\ORM\EntityManager;
use Otobank\Doctrine\Collections\AssociationAwareCriteriaInterface;
use Otobank\Doctrine\Collections\TargetAwareCriteriaInterface;
use PHPStan\Type\Doctrine\ObjectMetadataResolver;

trait ValidateTrait
{
    /** @var ObjectMetadataResolver */
    private $objectMetadataResolver;

    /** @var EntityManager */
    private $objectManager;

    public function __construct(ObjectMetadataResolver $objectMetadataResolver)
    {
        $this->objectMetadataResolver = $objectMetadataResolver;

        $objectManager = $this->objectMetadataResolver->getObjectManager();

        if ($objectManager === null) {
            throw new \PHPStan\ShouldNotHappenException('Please provide the "objectManagerLoader" setting.');
        }
        if (! $objectManager instanceof EntityManager) {
            throw new \PHPStan\ShouldNotHappenException('ObjectManager should be EntityManager');
        }

        $this->objectManager = $objectManager;
    }

    /**
     * @param class-string $criteriaClassName
     * @param list<string> $fields
     *
     * @return list<string>
     */
    private function validateFields(string $criteriaClassName, array $fields) : array
    {
        if (! is_a($criteriaClassName, TargetAwareCriteriaInterface::class, true)) {
            return [];
        }

        $targetClass = $criteriaClassName::getTargetClass();

        $assocMap = [];
        if (is_a($criteriaClassName, AssociationAwareCriteriaInterface::class, true)) {
            $assocMap = $criteriaClassName::getAssociationMap();
        }

        $messages = [];

        foreach ($fields as $field) {
            $usingAssoc = false;
            $usingEmbedded = false;

            if (strpos($field, '.') !== false) {
                list($alias, $assocField) = explode('.', $field);

                if (array_key_exists($alias, $assocMap)) {
                    // Using association object
                    $usingAssoc = true;
                    $assocName = $assocMap[$alias];
                    $meta = $this->objectManager->getClassMetadata($targetClass);
                    $assocationTargetClass = $meta->getAssociationTargetClass($assocName);
                    if (! $assocationTargetClass) {
                        throw new \PHPStan\ShouldNotHappenException('association target class not found');
                    }
                    $targetClass = $assocationTargetClass;
                    $field = $assocField;
                } else {
                    $meta = $this->objectManager->getClassMetadata($targetClass);
                    if (array_key_exists($alias, $meta->embeddedClasses)) {
                        // Using embedded
                        $usingEmbedded = true;
                    }
                }
            }

            $dqlOk = $this->isAccessibleForDql($targetClass, $field);

            if ($usingAssoc || $usingEmbedded) {
                // If you are using association objects or Embedded,
                // it can only apply to QueryBuilder.
                // Therefore, no accessor checks are required.
                $phpOK = true;
            } else {
                $phpOK = $this->isAccessibleForPhp($targetClass, $field);
            }

            if (! $dqlOk) {
                $messages[] = sprintf('%s::$%s field is missing', $targetClass, $field);
            }

            if (! $phpOK) {
                $messages[] = sprintf('%s::$%s accessor is missing', $targetClass, $field);
            }
        }

        return $messages;
    }

    private function isAccessibleForDql(string $targetClass, string $field) : bool
    {
        $classMetadata = $this->objectManager->getClassMetadata($targetClass);

        return $classMetadata->hasField($field) || $classMetadata->hasAssociation($field);
    }

    private function isAccessibleForPhp(string $targetClass, string $field) : bool
    {
        // See: Doctrine\Common\Collections\Expr\ClosureExpressionVisitor::getObjectFieldValue
        foreach (['get', 'is'] as $prefix) {
            $accessor = $prefix . $field;

            if (method_exists($targetClass, $accessor)) {
                return true;
            }
        }

        if (preg_match('/^is[A-Z]+/', $field) === 1 && method_exists($targetClass, $field)) {
            return true;
        }

        return false;
    }
}
