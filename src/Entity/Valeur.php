<?php

namespace App\Entity;

use App\Repository\ValeurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ValeurRepository::class)]
class Valeur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text', nullable: true)]
    private $val;

    #[ORM\ManyToOne(targetEntity: Entry::class, inversedBy: 'valeurs')]
    private $entry;

    #[ORM\ManyToOne(targetEntity: Column::class, inversedBy: 'valeurs')]
    #[ORM\JoinColumn(nullable: false)]
    private $col;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVal(): ?string
    {
        return $this->val;
    }

    public function setVal(?string $val): self
    {
        $this->val = $val;

        return $this;
    }

    public function getEntry(): ?Entry
    {
        return $this->entry;
    }

    public function setEntry(?Entry $entry): self
    {
        $this->entry = $entry;

        return $this;
    }

    public function getCol(): ?Column
    {
        return $this->col;
    }

    public function setCol(?Column $col): self
    {
        $this->col = $col;

        return $this;
    }
}
