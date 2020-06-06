<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use PHPStan\Rules\Rule;
use PHPStan\Testing\RuleTestCase;

class ProhibitCollectionCallWithAssociationsRuleTest extends RuleTestCase
{
    public function getRule() : Rule
    {
        return new ProhibitCollectionCallWithAssociationsRule();
    }

    public function testWithoutAssociation() : void
    {
        $this->analyse(
            [
                __DIR__ . '/Asset/CollectionEntityWithoutAssociation.php',
            ],
            [
            ]
        );
    }

    public function testWithAssociation() : void
    {
        $this->analyse(
            [
                __DIR__ . '/Asset/CollectionEntityWithAssociation.php',
            ],
            [
                ['Doctrine\Common\Collections\Collection::matching is not accept Otobank\Doctrine\Collections\AssociationAwareCriteriaInterface', 22],
                ['Doctrine\Common\Collections\Collection::matching is not accept Otobank\Doctrine\Collections\AssociationAwareCriteriaInterface', 29],
                ['Doctrine\Common\Collections\Collection::matching is not accept Otobank\Doctrine\Collections\AssociationAwareCriteriaInterface', 36],
            ]
        );
    }
}
