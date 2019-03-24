<?php

namespace App\Entity;

class Cannon
{
    private $id;

    private $army;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArmy(): ?Army
    {
        return $this->army;
    }

    public function setArmy(?Army $army): self
    {
        $this->army = $army;

        return $this;
    }
}
