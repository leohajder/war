<?php

namespace App\Entity;

class BattleOutcome
{
    const SURVIVED = 'survived';
    const DIED = 'died';
    
    private $id;

    private $outcome;

    private $battle;

    private $soldier;

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

    public function getBattle(): ?Battle
    {
        return $this->battle;
    }

    public function setBattle(?Battle $battle): self
    {
        $this->battle = $battle;

        return $this;
    }

    public function getSoldier(): ?Soldier
    {
        return $this->soldier;
    }

    public function setSoldier(?Soldier $soldier): self
    {
        $this->soldier = $soldier;

        return $this;
    }
}
