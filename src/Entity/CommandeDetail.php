<?php

namespace App\Entity;

use App\Repository\CommandeDetailRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeDetailRepository::class)]
class CommandeDetail
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    #[ORM\Column]
    private ?int $quantiteRupture = null;

    #[ORM\ManyToOne(inversedBy: 'commandeDetails')]
    private ?Produits $produits = null;

    #[ORM\ManyToOne(inversedBy: 'commandeDetail')]
    private ?Commande $commande = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }

    public function getQuantiteRupture(): ?int
    {
        return $this->quantiteRupture;
    }

    public function setQuantiteRupture(int $quantiteRupture): static
    {
        $this->quantiteRupture = $quantiteRupture;

        return $this;
    }

    public function getCommande(): ?Commande
    {
        return $this->commande;
    }

    public function setCommande(?Commande $commande): static
    {
        $this->commande = $commande;

        return $this;
    }

    public function getProduits(): ?Produits
    {
        return $this->produits;
    }

    public function setProduits(?Produits $produits): static
    {
        $this->produits = $produits;

        return $this;
    }

}
