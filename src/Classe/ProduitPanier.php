<?php


namespace App\Classe;


use App\Entity\Produits;
use Doctrine\Persistence\ManagerRegistry;
///-----------------
//classe qui represente un produit dans le panier avec une quantite 
//-------------------
class ProduitPanier 
{
    
    private $quantite;
    private $produit;

    //--------------------------------------
    //fonction qui cree un produit pour le panier avec un id et une quantite
    // le produits est recupere de la base de donnee
    //----------------------------------------
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