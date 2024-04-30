<?php

namespace App\Entity;

use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProduitRepository::class)]
class Produit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(targetEntity: CommandeDetail::class, mappedBy: 'produit')]
    private Collection $commandeDetail;

    public function __construct()
    {
        $this->commandeDetail = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, CommandeDetail>
     */
    public function getCommandeDetail(): Collection
    {
        return $this->commandeDetail;
    }

    public function addCommandeDetail(CommandeDetail $commandeDetail): static
    {
        if (!$this->commandeDetail->contains($commandeDetail)) {
            $this->commandeDetail->add($commandeDetail);
            $commandeDetail->setProduit($this);
        }

        return $this;
    }

    public function removeCommandeDetail(CommandeDetail $commandeDetail): static
    {
        if ($this->commandeDetail->removeElement($commandeDetail)) {
            // set the owning side to null (unless already changed)
            if ($commandeDetail->getProduit() === $this) {
                $commandeDetail->setProduit(null);
            }
        }

        return $this;
    }
}
