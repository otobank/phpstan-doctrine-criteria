<?php

namespace Otobank\PHPStan\Doctrine\Rules\Asset;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class EmbeddedEntity
{
    /**
     * @ORM\Column(name="baz", type="string")
     */
    private $baz;

    public function getBaz() : string
    {
        return $this->baz;
    }
}
