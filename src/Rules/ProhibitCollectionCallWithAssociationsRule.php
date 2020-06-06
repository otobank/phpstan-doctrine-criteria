<?php

namespace Otobank\PHPStan\Doctrine\Rules;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Selectable;
use Otobank\Doctrine\Collections\AssociationAwareCriteriaInterface;
use PhpParser\Node;
use PhpParser\Node\Expr\MethodCall;
use PHPStan\Analyser\Scope;
use PHPStan\TrinaryLogic;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\ObjectType;
use PHPStan\Type\UnionType;

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

        $calledOnType = $scope->getType($node->var);

        $collectionType = new ObjectType(Collection::class);
        $selectableType = new ObjectType(Selectable::class);

        if ($calledOnType instanceof ObjectType) {
            $isCollectionType = $collectionType->isSuperTypeOf($calledOnType);
            $isSelectableType = $selectableType->isSuperTypeOf($calledOnType);
        } elseif (
            $calledOnType instanceof IntersectionType
            || $calledOnType instanceof UnionType
        ) {
            $results1 = [];
            $results2 = [];
            foreach ($calledOnType->getTypes() as $childType) {
                $results1[] = $collectionType->isSuperTypeOf($childType);
                $results2[] = $selectableType->isSuperTypeOf($childType);
            }
            $isCollectionType = TrinaryLogic::createNo()->or(...$results1);
            $isSelectableType = TrinaryLogic::createNo()->or(...$results2);
        } else {
            return [];
        }

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
