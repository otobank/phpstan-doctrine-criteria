<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;
use Otobank\Doctrine\Collections\AssociationAwareCriteriaInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\Type\ObjectType;

/**
 * Prohibit `Collection::matching(AssociationAwareCriteriaInterface $criteria)`
 */
class ProhibitCollectionCallWithAssociationsRule implements \PHPStan\Rules\Rule
{
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
        if (! $node->name instanceof Node\Identifier) {
            return [];
        }

        $type = $scope->getType($node);

        if (! $type instanceof ObjectType) {
            return [];
        }

        $isCollectionType = (new ObjectType(Collection::class))->isSuperTypeOf($type);
        $isSelectableType = (new ObjectType(Selectable::class))->isSuperTypeOf($type);

        $methodName = $node->name->name;

        if (($isCollectionType->yes() || $isSelectableType->yes())
            && $methodName === 'matching'
        ) {
            $criteriaType = $scope->getType($node->args[0]->value);
            $isQueryBuilderType = (new ObjectType(AssociationAwareCriteriaInterface::class))->isSuperTypeOf($criteriaType);

            if ($isQueryBuilderType->yes()) {
                return [sprintf('%s::matching is not accept %s', Collection::class, AssociationAwareCriteriaInterface::class)];
            }
        }

        return [];
    }
}
