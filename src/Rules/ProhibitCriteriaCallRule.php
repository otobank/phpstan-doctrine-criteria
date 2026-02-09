<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use Doctrine\Common\Collections\Criteria;
use Otobank\Doctrine\Collections\TargetAwareCriteriaInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;

/**
 * @template-implements \PHPStan\Rules\Rule<MethodCall>
 */
class ProhibitCriteriaCallRule implements \PHPStan\Rules\Rule
{
    public function getNodeType() : string
    {
        return MethodCall::class;
    }

    /**
     * @param MethodCall $node
     *
     * @return (string|\PHPStan\Rules\RuleError)[]
     */
    public function processNode(Node $node, Scope $scope) : array
    {
        if (! $node->name instanceof Node\Identifier) {
            return [];
        }

        $calledOnType = $scope->getType($node->var);

        if (! $calledOnType instanceof ObjectType) {
            return [];
        }

        if (! (new ObjectType(Criteria::class))->isSuperTypeOf($calledOnType)->yes()) {
            return [];
        }

        if (! (new ObjectType(TargetAwareCriteriaInterface::class))->isSuperTypeOf($calledOnType)->yes()) {
            return [
                sprintf('Use %s instead of %s', TargetAwareCriteriaInterface::class, Criteria::class),
            ];
        }

        return [];
    }
}
