<?php

namespace App\Entity;

use App\Repository\ColumnRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColumnRepository::class)]
#[ORM\Table(name: '`column`')]
class Column
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'text')]
    private $headerName;

    #[ORM\Column(type: 'integer')]
    private $ordre;

    #[ORM\OneToMany(mappedBy: 'col', targetEntity: Valeur::class, orphanRemoval: true)]
    private $valeurs;

    public function __construct()
    {
        $this->valeurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeaderName(): ?string
    {
        return $this->headerName;
    }

    public function setHeaderName(string $headerName): self
    {
        $this->headerName = $headerName;

        return $this;
    }

    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    public function setOrdre(int $ordre): self
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * @return Collection<int, Value>
     */
    public function getValeurs(): Collection
    {
        return $this->valeurs;
    }

    public function addValeur(Valeur $valeur): self
    {
        if (!$this->valeurs->contains($valeur)) {
            $this->valeurs[] = $valeur;
            $valeur->setCol($this);
        }

        return $this;
    }

    public function removeValeur(Valeur $valeur): self
    {
        if ($this->valeurs->removeElement($valeur)) {
            // set the owning side to null (unless already changed)
            if ($valeur->getCol() === $this) {
                $valeur->setCol(null);
            }
        }

        return $this;
    }
}
