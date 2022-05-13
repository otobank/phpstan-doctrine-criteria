<?php

namespace Otobank\PHPStan\Doctrine\Rules\Asset;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class AcmeEntity
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", options={"unsigned": true})
     */
    private $id;

    /**
     * @ORM\Column(name="foo", type="string")
     */
    private $foo;

    /**
     * @ORM\Column(name="bar", type="string")
     */
    private $bar;

    /**
     * @ORM\Embedded(class="EmbeddedEntity")
     */
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
