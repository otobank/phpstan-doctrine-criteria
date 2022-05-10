<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use Doctrine\Common\Collections\Expr\Comparison;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\TypeWithClassName;

/**
 * ```
 *   TargetAwareCriteria::expr()->eq($field, $value); // Validate $field
 * ```
 *
 * @template-implements \PHPStan\Rules\Rule<MethodCall>
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

        $args = $node->getArgs();

        if (! isset($args[0])) {
            return [];
        }

        $argType = $scope->getType($args[0]->value);

        if (! $argType instanceof ConstantStringType) {
            return [];
        }

        $field = $argType->getValue();

        if (isset($node->var->class)) {
            $criteriaClassName = $scope->resolveName($node->var->class);
        } elseif (isset($node->var->var)) {
            $varType = $scope->getType($node->var->var);
            assert($varType instanceof TypeWithClassName);
            $criteriaClassName = $varType->getClassName();
        } else {
            return [];
        }

        assert(class_exists($criteriaClassName));

        return $this->validateFields($criteriaClassName, [$field]);
    }
}
