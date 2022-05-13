<?php

namespace Otobank\PHPStan\Doctrine\Type;

use Doctrine\Common\Collections\Criteria;
use PhpParser\Node\Expr\StaticCall;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\MethodReflection;
use PHPStan\Type\DynamicStaticMethodReturnTypeExtension;
use PHPStan\Type\ObjectType;
use PHPStan\Type\Type;

/**
 * Even if you inherit `criteria` return the correct class name.
 */
class CriteriaStaticMethodReturnTypeExtension implements DynamicStaticMethodReturnTypeExtension
{
    public function getClass() : string
    {
        return Criteria::class;
    }

    public function isStaticMethodSupported(MethodReflection $methodReflection) : bool
    {
        return $methodReflection->getName() === 'create';
    }

    public function getTypeFromStaticMethodCall(
        MethodReflection $methodReflection,
        StaticCall $methodCall,
        Scope $scope
    ) : Type {
        assert($methodCall->class instanceof \PhpParser\Node\Name);

        return new ObjectType($scope->resolveName($methodCall->class));
    }
}
