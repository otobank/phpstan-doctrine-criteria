<?php

namespace Otobank\PHPStan\Doctrine\Rules\Asset;

class AcmeEntity
{
    private $foo;
    private $bar;
    private $embedded;

    public function getFoo() : string
    {
        return $this->foo;
    }

    public function getEmbedded() : EmbeddedEntity
    {
        return $this->embedded;
    }
}
