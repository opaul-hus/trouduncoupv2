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
    private ?int $qunatite = null;

    #[ORM\Column]
    private ?int $qunatiteRupture = null;

    #[ORM\ManyToOne(inversedBy: 'detailCommande')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Commande $commande = null;

    #[ORM\ManyToOne(inversedBy: 'commandeDetail')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Produit $produit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQunatite(): ?int
    {
        return $this->qunatite;
    }

    public function setQunatite(int $qunatite): static
    {
        $this->qunatite = $qunatite;

        return $this;
    }

    public function getQunatiteRupture(): ?int
    {
        return $this->qunatiteRupture;
    }

    public function setQunatiteRupture(int $qunatiteRupture): static
    {
        $this->qunatiteRupture = $qunatiteRupture;

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

    public function getProduit(): ?Produit
    {
        return $this->produit;
    }

    public function setProduit(?Produit $produit): static
    {
        $this->produit = $produit;

        return $this;
    }
}
