<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use Doctrine\Common\Persistence\ObjectManager;
use Otobank\Doctrine\Collections\AssociationAwareCriteriaInterface;
use Otobank\Doctrine\Collections\TargetAwareCriteriaInterface;
use PHPStan\Type\Doctrine\ObjectMetadataResolver;

trait ValidateTrait
{
    /** @var ObjectMetadataResolver */
    private $objectMetadataResolver;

    /** @var ObjectManager */
    private $objectManager;

    public function __construct(ObjectMetadataResolver $objectMetadataResolver)
    {
        $this->objectMetadataResolver = $objectMetadataResolver;

        $objectManager = $this->objectMetadataResolver->getObjectManager();

        if ($objectManager === null) {
            throw new \PHPStan\ShouldNotHappenException('Please provide the "objectManagerLoader" setting.');
        }

        $this->objectManager = $objectManager;
    }

    private function validateFields(string $criteriaClassName, array $fields) : array
    {
        if (! is_a($criteriaClassName, TargetAwareCriteriaInterface::class, true)) {
            return [];
        }

        $targetClass = $criteriaClassName::getTargetClass();

        $assocAliases = [];
        if (is_a($criteriaClassName, AssociationAwareCriteriaInterface::class, true)) {
            $assocMap = $criteriaClassName::getAssociationMap();
        }

        $messages = [];

        foreach ($fields as $field) {
            $dqlOk = false;
            $phpOK = false;
            $isAssoc = false;

            // 関連オブジェクトを条件に使っている
            if (strpos($field, '.') !== false) {
                list($alias, $assocField) = explode('.', $field);

                if (array_key_exists($alias, $assocMap)) {
                    $isAssoc = true;
                    $assocName = $assocMap[$alias];
                    $meta = $this->objectManager->getClassMetadata($targetClass);
                    $targetClass = $meta->getAssociationTargetClass($assocName);
                    $field = $assocField;
                }
            }

            $dqlOk = $this->isAccessibleForDql($targetClass, $field);

            if ($isAssoc) {
                // 関連オブジェクトを条件に使っている場合は QueryBuilder にしか適用できない
                // よって、アクセサのチェックは不要
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
        // See: Doctrine\Common\Collections\Expr::getObjectFieldValue
        foreach (['get', 'is'] as $prefix) {
            $accessor = $prefix . $field;

            if (method_exists($targetClass, $accessor)) {
                return true;
            }
        }

        return false;
    }
}
