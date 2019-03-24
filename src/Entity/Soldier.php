<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Soldier
{
    private $id;

    private $experience;

    private $alive;

    private $army;

    private $battleOutcomes;

    public function __construct()
    {
        $this->battleOutcomes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExperience(): ?int
    {
        return $this->experience;
    }

    public function setExperience(int $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getAlive(): ?bool
    {
        return $this->alive;
    }

    public function setAlive(bool $alive): self
    {
        $this->alive = $alive;

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

    /**
     * @return Collection|BattleOutcome[]
     */
    public function getBattleOutcomes(): Collection
    {
        return $this->battleOutcomes;
    }

    public function addBattleOutcome(BattleOutcome $battleOutcome): self
    {
        if (!$this->battleOutcomes->contains($battleOutcome)) {
            $this->battleOutcomes[] = $battleOutcome;
            $battleOutcome->setSoldier($this);
        }

        return $this;
    }

    public function removeBattleOutcome(BattleOutcome $battleOutcome): self
    {
        if ($this->battleOutcomes->contains($battleOutcome)) {
            $this->battleOutcomes->removeElement($battleOutcome);
            // set the owning side to null (unless already changed)
            if ($battleOutcome->getSoldier() === $this) {
                $battleOutcome->setSoldier(null);
            }
        }

        return $this;
    }
}
