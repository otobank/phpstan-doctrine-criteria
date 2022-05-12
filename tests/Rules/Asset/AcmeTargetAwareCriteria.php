<?php

namespace Otobank\PHPStan\Doctrine\Rules\Asset;

use Otobank\Doctrine\Collections\TargetAwareCriteria;
use Otobank\Doctrine\Collections\TargetAwareCriteriaInterface;

/**
 * @template-extends TargetAwareCriteriaInterface<AcmeEntity>
 */
class AcmeTargetAwareCriteria extends TargetAwareCriteria
{
    public static function getTargetClass() : string
    {
        return AcmeEntity::class;
    }
}
