<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class ProhibitCriteriaNewRuleTest extends RuleTestCase
{
    public function getRule() : Rule
    {
        return new ProhibitCriteriaNewRule();
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
                    9,
                ],
            ]
        );
    }
}
