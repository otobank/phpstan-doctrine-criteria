<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use Otobank\PHPStan\Doctrine\Type\CriteriaMethodReturnTypeExtension;
use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\Doctrine\ObjectMetadataResolver;

/**
 * @extends RuleTestCase<ValidateFieldCriteriaCallRule>
 */
class ValidateFieldCriteriaCallRuleTest extends RuleTestCase
{
    /**
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function getRule() : Rule
    {
        $objectMetadataResolver = new ObjectMetadataResolver(
            __DIR__ . '/Asset/entity-manager.php'
        );

        return new ValidateFieldCriteriaCallRule($objectMetadataResolver);
    }

    /**
     * @return list<CriteriaMethodReturnTypeExtension>
     */
    public function getDynamicMethodReturnTypeExtensions() : array
    {
        return [
            new CriteriaMethodReturnTypeExtension(),
        ];
    }

    public function testProcessNode() : void
    {
        $this->analyse(
            [
                __DIR__ . '/Asset/TargetAwareCriteriaCaller.php',
            ],
            [
                [
                    'Otobank\PHPStan\Doctrine\Rules\Asset\AcmeEntity::$bar accessor is missing',
                    16,
                ],
                [
                    'Otobank\PHPStan\Doctrine\Rules\Asset\AcmeEntity::$baz field is missing',
                    22,
                ],
                [
                    'Otobank\PHPStan\Doctrine\Rules\Asset\AcmeEntity::$baz accessor is missing',
                    22,
                ],
            ]
        );
    }
}
