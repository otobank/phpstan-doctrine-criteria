<?php

namespace Otobank\PHPStan\Doctrine\Rules\Asset;

class TargetAwareComparisonCaller
{
    public function accessFiledShouldNotRaiseError() : void
    {
        $criteria = new AcmeTargetAwareCriteria();
        $criteria->where(AcmeTargetAwareCriteria::expr()->eq('foo', 1));
    }

    public function nonAccessFiledShouldNotRaiseError() : void
    {
        $criteria = new AcmeTargetAwareCriteria();
        $criteria->where(AcmeTargetAwareCriteria::expr()->eq('bar', 1));
    }

    public function methodCallTargetAwareCriteria() : void
    {
        $criteria = new AcmeTargetAwareCriteria();
        $criteria->where(AcmeTargetAwareCriteria::expr()->eq('baz', 1));
    }
}
