<?php

namespace Otobank\PHPStan\Doctrine\Rules\Asset;

use Doctrine\Common\Collections\Collection;

class CollectionEntityWithAssociation
{
    /** @var Collection */
    private $items1;

    /** @var Collection|array<AcmeEntity> */
    private $items2;

    /** @var Collection&iterable<AcmeEntity> */
    private $items3;

    public function getItems1() : Collection
    {
        $criteria = AcmeAssociationAwareCriteria::create();

        return $this->items1->matching($criteria);
    }

    public function getItems2() : Collection
    {
        $criteria = AcmeAssociationAwareCriteria::create();

        return $this->items2->matching($criteria);
    }

    public function getItems3() : Collection
    {
        $criteria = AcmeAssociationAwareCriteria::create();

        return $this->items3->matching($criteria);
    }
}
