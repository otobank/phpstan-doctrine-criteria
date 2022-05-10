<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ProhibitComparisonNewRuleTest>
 */
class ProhibitComparisonNewRuleTest extends RuleTestCase
{
    public function getRule() : Rule
    {
        return new ProhibitComparisonNewRule();
    }

    public function testProcessNode() : void
    {
        $this->analyse(
            [
                __DIR__ . '/Asset/ComparisonCaller.php',
            ],
            [
                [
                    'Use `Otobank\Doctrine\Collections\TargetAwareCriteriaInterface:expr()` instead of `new Doctrine\Common\Collections\Expr\Comparison`',
                    12,
                ],
            ]
        );
    }
}
