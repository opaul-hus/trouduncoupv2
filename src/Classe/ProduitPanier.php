<?php


namespace App\Classe;


use App\Entity\Produits;
use Doctrine\Persistence\ManagerRegistry;

class ProduitPanier 
{
    private $quantite;
    private $produit;

    public function __construct(ManagerRegistry $doctrine,int $idProduit, int $quantite)
    {
        $em = $doctrine->getManager();
        $this->quantite = $quantite;
        $this->produit=$em->getRepository(Produits::class)->find($idProduit);

       
    
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): void
    {
        $this->quantite = $quantite;
    }

    public function getProduit(): Produits
    {
        return $this->produit;
    }

    public function getPrixTotal(): float
    {
        return $this->produit->getPrix() * $this->quantite;
    }
}