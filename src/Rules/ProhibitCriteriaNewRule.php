<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use Doctrine\Common\Collections\Criteria;
use Otobank\Doctrine\Collections\TargetAwareCriteriaInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\New_;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;

/**
 * Prohibit `new Criteria();`
 *
 * @template-implements \PHPStan\Rules\Rule<New_>
 */
class ProhibitCriteriaNewRule implements \PHPStan\Rules\Rule
{
    public function getNodeType() : string
    {
        return New_::class;
    }

    /**
     * @param New_ $node
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
            return [
                sprintf('Use %s instead of %s', TargetAwareCriteriaInterface::class, Criteria::class),
            ];
        }

        return [];
    }
}
