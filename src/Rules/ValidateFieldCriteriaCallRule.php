<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use Doctrine\Common\Collections\Criteria;
use Otobank\Doctrine\Collections\TargetAwareCriteriaInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantStringType;
use PHPStan\Type\ObjectType;

/**
 * ```
 *   // Validate fieldA
 *   TargetAwareCriteria::orderBy([
 *       'fieldA' => ...
 *   ]);
 * ```
 */
class ValidateFieldCriteriaCallRule implements \PHPStan\Rules\Rule
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

        if (! (new ObjectType(Criteria::class))->isSuperTypeOf($type)->yes()) {
            return [];
        }

        if (! (new ObjectType(TargetAwareCriteriaInterface::class))->isSuperTypeOf($type)->yes()) {
            return [];
        }

        $methodNameIdentifier = $node->name;
        if (! $methodNameIdentifier instanceof Node\Identifier) {
            return [];
        }

        $methodName = $methodNameIdentifier->toLowerString();
        if (! in_array($methodName, ['orderby'], true)) {
            return [];
        }

        if (! isset($node->args[0])) {
            return [];
        }

        $argType = $scope->getType($node->args[0]->value);

        if (! $argType instanceof ConstantArrayType) {
            return [];
        }

        if (count($argType->getKeyTypes()) === 0) {
            return [];
        }

        $fields = [];
        foreach ($argType->getKeyTypes() as $keyType) {
            if (! $keyType instanceof ConstantStringType) {
                continue;
            }

            $fields[] = $keyType->getValue();
        }

        $criteriaClassName = $type->getClassName();

        return $this->validateFields($criteriaClassName, $fields);
    }
}
