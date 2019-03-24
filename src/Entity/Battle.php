<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Battle
{
    private $id;

    private $war;

    private $outcomes;

    public function __construct()
    {
        $this->outcomes = new ArrayCollection();
    }

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

    /**
     * @return Collection|BattleOutcome[]
     */
    public function getOutcomes(): Collection
    {
        return $this->outcomes;
    }

    public function addOutcome(BattleOutcome $outcome): self
    {
        if (!$this->outcomes->contains($outcome)) {
            $this->outcomes[] = $outcome;
            $outcome->setBattle($this);
        }

        return $this;
    }

    public function removeOutcome(BattleOutcome $outcome): self
    {
        if ($this->outcomes->contains($outcome)) {
            $this->outcomes->removeElement($outcome);
            // set the owning side to null (unless already changed)
            if ($outcome->getBattle() === $this) {
                $outcome->setBattle(null);
            }
        }

        return $this;
    }
}
