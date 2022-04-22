<?php

namespace App\Entity;

use App\Repository\ActionOnColumnRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActionOnColumnRepository::class)]
class ActionOnColumn
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private $display;

    #[ORM\Column(type: 'boolean')]
    private $filter;

    #[ORM\Column(type: 'string', length: 255,columnDefinition: "ENUM('MOE','CAT')")]
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplay(): ?bool
    {
        return $this->display;
    }

    public function setDisplay(bool $display): self
    {
        $this->display = $display;

        return $this;
    }

    public function getFilter(): ?bool
    {
        return $this->filter;
    }

    public function setFilter(bool $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): self
    {
        $this->user = $user;

        return $this;
    }
}
