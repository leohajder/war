<?php

namespace App\Entity;

class Army
{
    private $id;

    private $tag;

    private $war;

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
}
