<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;
use Otobank\Doctrine\Collections\TargetAwareCriteriaInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeWithClassName;

/**
 * Prohibit `Criteria::expr()->eq($field, $value)`
 *
 * @template-implements \PHPStan\Rules\Rule<MethodCall>
 */
class ProhibitComparisonCallRule implements \PHPStan\Rules\Rule
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

        $type = $scope->getType($node);

        if (! $type instanceof ObjectType) {
            return [];
        }

        if (! (new ObjectType(Comparison::class))->isSuperTypeOf($type)->yes()) {
            return [];
        }

        if (isset($node->var->class)) {
            $criteriaClassName = $scope->resolveName($node->var->class);
        } elseif (isset($node->var->var)) {
            $varType = $scope->getType($node->var->var);
            assert($varType instanceof TypeWithClassName);
            $criteriaClassName = $varType->getClassName();
        } else {
            return ['Cannot get criteria class name'];
        }

        if ($criteriaClassName === Criteria::class) {
            return [
                sprintf('Use %s instead of %s', TargetAwareCriteriaInterface::class, Criteria::class),
            ];
        }

        return [];
    }
}
