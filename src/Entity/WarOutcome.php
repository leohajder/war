<?php

namespace App\Entity;

class WarOutcome
{
    private $id;

    private $outcome;

    private $war;

    private $army;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOutcome(): ?string
    {
        return $this->outcome;
    }

    public function setOutcome(string $outcome): self
    {
        $this->outcome = $outcome;

        return $this;
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
