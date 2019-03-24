<?php

namespace App\Entity;

class Battle
{
    private $id;

    private $war;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWar(): ?War
    {
        return $this->war;
    }

    public function setWar(?War $war): self
    {
        $this->war = $war;

        return $this;
    }
}
