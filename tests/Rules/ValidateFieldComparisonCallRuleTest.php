<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;
use PHPStan\Type\Doctrine\ObjectMetadataResolver;

/**
 * @extends RuleTestCase<ValidateFieldComparisonCallRule>
 */
class ValidateFieldComparisonCallRuleTest extends RuleTestCase
{
    /**
     * @throws \PHPStan\ShouldNotHappenException
     */
    public function getRule() : Rule
    {
        $objectMetadataResolver = new ObjectMetadataResolver(
            __DIR__ . '/Asset/entity-manager.php'
        );

        return new ValidateFieldComparisonCallRule($objectMetadataResolver);
    }

    public function testProcessNode() : void
    {
        $this->analyse(
            [
                __DIR__ . '/Asset/TargetAwareComparisonCaller.php',
            ],
            [
                [
                    'Otobank\PHPStan\Doctrine\Rules\Asset\AcmeEntity::$bar accessor is missing',
                    17,
                ],
                [
                    'Otobank\PHPStan\Doctrine\Rules\Asset\AcmeEntity::$bar accessor is missing',
                    18,
                ],
                [
                    'Otobank\PHPStan\Doctrine\Rules\Asset\AcmeEntity::$baz field is missing',
                    24,
                ],
                [
                    'Otobank\PHPStan\Doctrine\Rules\Asset\AcmeEntity::$baz accessor is missing',
                    24,
                ],
                [
                    'Otobank\PHPStan\Doctrine\Rules\Asset\AcmeEntity::$baz field is missing',
                    25,
                ],
                [
                    'Otobank\PHPStan\Doctrine\Rules\Asset\AcmeEntity::$baz accessor is missing',
                    25,
                ],
            ]
        );
    }
}
