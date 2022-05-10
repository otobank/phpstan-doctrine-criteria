<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use Doctrine\Common\Collections\Expr\Comparison;
use Otobank\Doctrine\Collections\TargetAwareCriteriaInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;

/**
 * Prohibit `new Comparison();`
 *
 * @template-implements \PHPStan\Rules\Rule<New_>
 */
class ProhibitComparisonNewRule implements \PHPStan\Rules\Rule
{
    public function getNodeType() : string
    {
        return New_::class;
    }

    /**
     * @param \PhpParser\Node\Expr\New_ $node
     *
     * @return (string|\PHPStan\Rules\RuleError)[]
     */
    public function processNode(Node $node, Scope $scope) : array
    {
        $type = $scope->getType($node);

        if (! $type instanceof ObjectType) {
            return [];
        }

        if ((new ObjectType(Comparison::class))->isSuperTypeOf($type)->yes()) {
            return [
                sprintf('Use `%s:expr()` instead of `new %s`', TargetAwareCriteriaInterface::class, Comparison::class),
            ];
        }

        return [];
    }
}
