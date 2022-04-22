<?php

namespace App\Entity;

use App\Repository\EntryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntryRepository::class)]
class Entry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $indice;

    #[ORM\ManyToOne(targetEntity: File::class, inversedBy: 'entries')]
    private $file;

    #[ORM\OneToMany(mappedBy: 'entry', targetEntity: Valeur::class)]
    private $valeurs;

    public function __construct()
    {
        $this->valeurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIndice(): ?int
    {
        return $this->indice;
    }

    public function setIndice(int $indice): self
    {
        $this->indice = $indice;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return Collection<int, Valeur>
     */
    public function getValeurs(): Collection
    {
        return $this->valeurs;
    }

    public function addValeur(Valeur $valeur): self
    {
        if (!$this->valeurs->contains($valeur)) {
            $this->valeurs[] = $valeur;
            $valeur->setEntry($this);
        }

        return $this;
    }

    public function removeValeur(Valeur $valeur): self
    {
        if ($this->valeurs->removeElement($valeur)) {
            // set the owning side to null (unless already changed)
            if ($valeur->getEntry() === $this) {
                $valeur->setEntry(null);
            }
        }

        return $this;
    }
}
