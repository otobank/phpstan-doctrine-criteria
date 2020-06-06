<?php

namespace Otobank\PHPStan\Doctrine\Rules\Asset;

use Otobank\Doctrine\Collections\AssociationAwareCriteria;

class AcmeAssociationAwareCriteria extends AssociationAwareCriteria
{
    public static function getTargetClass() : string
    {
        return AcmeEntity::class;
    }

    public static function getAssociationMap() : array
    {
        return [
        ];
    }
}
