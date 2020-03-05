<?php

namespace Otobank\PHPStan\Doctrine\Rules\Asset;

class NonTargetAwareCriteriaCaller
{
    public function methodCallNonTargetAwareCriteria() : void
    {
        $criteria = new NonTargetAwareCriteria();
        $criteria->where(NonTargetAwareCriteria::expr()->eq('foo', 1));
    }
}
