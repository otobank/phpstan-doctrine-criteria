<?php

namespace Otobank\PHPStan\Doctrine\Rules\Asset;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;

class ComparisonCaller
{
    public function new() : void
    {
        new Comparison('field', 'eq', 1);
    }

    public function call() : void
    {
        Criteria::expr()->eq('field', 'value');
    }
}
