<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

/**
 * @extends RuleTestCase<ProhibitCriteriaCallRuleTest>
 */
class ProhibitCriteriaCallRuleTest extends RuleTestCase
{
    public function getRule() : Rule
    {
        return new ProhibitCriteriaCallRule();
    }

    public function testProcessNode() : void
    {
        $this->analyse(
            [
                __DIR__ . '/Asset/NonTargetAwareCriteriaCaller.php',
            ],
            [
                [
                    'Use Otobank\Doctrine\Collections\TargetAwareCriteriaInterface instead of Doctrine\Common\Collections\Criteria',
                    10,
                ],
            ]
        );
    }
}
