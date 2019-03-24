<?php

namespace App\Entity;

class BattleOutcome
{
    private $id;

    private $survived;

    private $battle;

    private $soldier;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurvived(): ?bool
    {
        return $this->survived;
    }

    public function setSurvived(bool $survived): self
    {
        $this->survived = $survived;

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
