<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class War
{
    private $id;

    private $battles;

    private $armies;

    private $outcomes;

    public function __construct()
    {
        $this->battles = new ArrayCollection();
        $this->armies = new ArrayCollection();
        $this->outcomes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Battle[]
     */
    public function getBattles(): Collection
    {
        return $this->battles;
    }

    public function addBattle(Battle $battle): self
    {
        if (!$this->battles->contains($battle)) {
            $this->battles[] = $battle;
            $battle->setWar($this);
        }

        return $this;
    }

    public function removeBattle(Battle $battle): self
    {
        if ($this->battles->contains($battle)) {
            $this->battles->removeElement($battle);
            // set the owning side to null (unless already changed)
            if ($battle->getWar() === $this) {
                $battle->setWar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Army[]
     */
    public function getArmies(): Collection
    {
        return $this->armies;
    }

    public function addArmy(Army $army): self
    {
        if (!$this->armies->contains($army)) {
            $this->armies[] = $army;
            $army->setWar($this);
        }

        return $this;
    }

    public function removeArmy(Army $army): self
    {
        if ($this->armies->contains($army)) {
            $this->armies->removeElement($army);
            // set the owning side to null (unless already changed)
            if ($army->getWar() === $this) {
                $army->setWar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WarOutcome[]
     */
    public function getOutcomes(): Collection
    {
        return $this->outcomes;
    }

    public function addOutcome(WarOutcome $outcome): self
    {
        if (!$this->outcomes->contains($outcome)) {
            $this->outcomes[] = $outcome;
            $outcome->setWar($this);
        }

        return $this;
    }

    public function removeOutcome(WarOutcome $outcome): self
    {
        if ($this->outcomes->contains($outcome)) {
            $this->outcomes->removeElement($outcome);
            // set the owning side to null (unless already changed)
            if ($outcome->getWar() === $this) {
                $outcome->setWar(null);
            }
        }

        return $this;
    }
}
