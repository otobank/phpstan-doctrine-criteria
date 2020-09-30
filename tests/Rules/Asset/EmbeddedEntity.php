<?php

namespace Otobank\PHPStan\Doctrine\Rules\Asset;

class EmbeddedEntity
{
    private $baz;

    public function getBaz() : string
    {
        return $this->baz;
    }
}
