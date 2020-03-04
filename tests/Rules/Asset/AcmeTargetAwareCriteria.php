<?php

namespace Otobank\PHPStan\Doctrine\Rules\Asset;

use Otobank\Doctrine\Collections\TargetAwareCriteria;

class AcmeTargetAwareCriteria extends TargetAwareCriteria
{
    public static function getTargetClass() : string
    {
        return AcmeEntity::class;
    }
}
