<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Army
{
    private $id;

    private $tag;

    private $war;

    private $soldiers;

    private $warOutcomes;

    public function __construct()
    {
        $this->soldiers = new ArrayCollection();
        $this->warOutcomes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTag(): ?string
    {
        return $this->tag;
    }

    public function setTag(string $tag): self
    {
        $this->tag = $tag;

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

    /**
     * @return Collection|Soldier[]
     */
    public function getSoldiers(): Collection
    {
        return $this->soldiers;
    }

    public function addSoldier(Soldier $soldier): self
    {
        if (!$this->soldiers->contains($soldier)) {
            $this->soldiers[] = $soldier;
            $soldier->setArmy($this);
        }

        return $this;
    }

    public function removeSoldier(Soldier $soldier): self
    {
        if ($this->soldiers->contains($soldier)) {
            $this->soldiers->removeElement($soldier);
            // set the owning side to null (unless already changed)
            if ($soldier->getArmy() === $this) {
                $soldier->setArmy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|WarOutcome[]
     */
    public function getWarOutcomes(): Collection
    {
        return $this->warOutcomes;
    }

    public function addWarOutcome(WarOutcome $warOutcome): self
    {
        if (!$this->warOutcomes->contains($warOutcome)) {
            $this->warOutcomes[] = $warOutcome;
            $warOutcome->setArmy($this);
        }

        return $this;
    }

    public function removeWarOutcome(WarOutcome $warOutcome): self
    {
        if ($this->warOutcomes->contains($warOutcome)) {
            $this->warOutcomes->removeElement($warOutcome);
            // set the owning side to null (unless already changed)
            if ($warOutcome->getArmy() === $this) {
                $warOutcome->setArmy(null);
            }
        }

        return $this;
    }
}
