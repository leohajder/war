<?php

namespace App\Entity;

class BattleOutcome
{
    private $id;

    private $survived;

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
}
