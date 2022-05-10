<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ProhibitComparisonCallRuleTest>
 */
class ProhibitComparisonCallRuleTest extends RuleTestCase
{
    public function getRule() : Rule
    {
        return new ProhibitComparisonCallRule();
    }

    public function testProcessNode() : void
    {
        $this->analyse(
            [
                __DIR__ . '/Asset/ComparisonCaller.php',
            ],
            [
                [
                    'Use Otobank\Doctrine\Collections\TargetAwareCriteriaInterface instead of Doctrine\Common\Collections\Criteria',
                    17,
                ],
            ]
        );
    }
}
