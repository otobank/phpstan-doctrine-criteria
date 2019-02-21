<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use Doctrine\Common\Collections\Expr\Comparison;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;

/**
 * ```
 *   TargetAwareCriteria::expr()->eq($field, $value); // Validate $field
 * ```
 */
class ValidateFieldComparisonCallRule implements \PHPStan\Rules\Rule
{
    use ValidateTrait;

    public function getNodeType() : string
    {
        return MethodCall::class;
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     * @param \PHPStan\Analyser\Scope         $scope
     *
     * @return (string|\PHPStan\Rules\RuleError)[]
     */
    public function processNode(Node $node, Scope $scope) : array
    {
        $type = $scope->getType($node);

        if (! $type instanceof ObjectType) {
            return [];
        }

        if (! (new ObjectType(Comparison::class))->isSuperTypeOf($type)->yes()) {
            return [];
        }

        $methodNameIdentifier = $node->name;
        if (! $methodNameIdentifier instanceof Node\Identifier) {
            return [];
        }

        if (! isset($node->args[0])) {
            return [];
        }

        $argType = $scope->getType($node->args[0]->value);

        if (! $argType instanceof ConstantStringType) {
            return [];
        }

        $field = $argType->getValue();

        if (isset($node->var->class)) {
            $criteriaClassName = $scope->resolveName($node->var->class);
        } elseif (isset($node->var->var)) {
            $criteriaClassName = $scope->getType($node->var->var)->getClassName();
        } else {
            $criteriaClassName = '';
        }

        return $this->validateFields($criteriaClassName, [$field]);
    }
}
