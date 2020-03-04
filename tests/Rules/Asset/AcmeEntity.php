<?php


namespace Otobank\PHPStan\Doctrine\Rules\Asset;


class AcmeEntity
{
    private $foo;
    private $bar;

    public function getFoo() : string
    {
        return $this->foo;
    }
}