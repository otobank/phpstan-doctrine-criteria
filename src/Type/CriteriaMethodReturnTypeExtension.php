<?php

namespace Otobank\PHPStan\Doctrine\Type;

use Doctrine\Common\Collections\Criteria;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicMethodReturnTypeExtension;
use PHPStan\Type\Type;

/**
 * Even if you inherit `criteria` return the correct class name.
 */
class CriteriaMethodReturnTypeExtension implements DynamicMethodReturnTypeExtension
{
    public function getClass() : string
    {
        return Criteria::class;
    }

    public function isMethodSupported(MethodReflection $methodReflection) : bool
    {
        return in_array($methodReflection->getName(), [
            'where',
            'andWhere',
            'orWhere',
            'orderBy',
            'setFirstResult',
            'setMaxResults',
        ], true);
    }

    public function getTypeFromMethodCall(
        MethodReflection $methodReflection,
        MethodCall $methodCall,
        Scope $scope
    ) : Type {
        return $scope->getType($methodCall->var);
    }
}
