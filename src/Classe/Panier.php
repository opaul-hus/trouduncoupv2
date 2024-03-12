<?php



namespace App\Classe;

define('FRAIS_LIV', '9.99');

use App\Classe\ProduitPanier;
use App\Entity\Produits;



class Panier{


    

    private $panierItems = [];


    public function __construct(){
        $this->panierItems = [];
    }

    //--------------------------------------
    //fonction qui verifie si un produit est deja dans le panier
    //----------------------------------------
    private function contientProduit(ProduitPanier $produit){
        foreach ($this->panierItems as $item){
            if ($item->getProduit()->getId() == $produit->getProduit()->getId()){
                return true;
            }
        }
        return false;
  
    }


    //--------------------------------------
    //fonction ajoute un produit au panier
    //----------------------------------------
    public function add(ProduitPanier $produitPanier){
        if ($this->contientProduit($produitPanier)) {
            foreach ($this->panierItems as $item){
                if ($item->getProduit()->getId() == $produitPanier->getProduit()->getId()){
                    $item->setQuantite($item->getQuantite() + $produitPanier->getQuantite());
                }
            } 
        }
        else{
            $this->panierItems[] = $produitPanier;
        }
    }

    //--------------------------------------
    //fonction retire un produit au panier
    //----------------------------------------
    public function remove(Produits $produit){
        foreach ($this->panierItems as $item){
            if ($item->getProduit()->getId() == $produit->getId()){
                $key = array_search($item, $this->panierItems);
                unset($this->panierItems[$key]);
            }
        }
    }

    //--------------------------------------
    //fonction qui retourne les items du panier
    //----------------------------------------
    public function getPanierItems(){
  
        return $this->panierItems;
    }   

    
    //--------------------------------------
    //fonction qui retourne le total du panier avant taxes
    //----------------------------------------
    public function getTotal(){
        $total = 0.00;
        foreach ($this->panierItems as $item){
            $total += $item->getProduit()->getPrix() * $item->getQuantite();
        }
        return $total;
    }

    //--------------------------------------
    //fonction qui vide le panier
    //----------------------------------------
    public function clear(){
        $this->panierItems = [];
    }

    //--------------------------------------
    //fonction qui calcule la tps
    //----------------------------------------
    public function getTps(){
        $soustotal = $this->getTotal()+FRAIS_LIV;
        return  $soustotal * 0.05;
    }
    //--------------------------------------
    //fonction qui calcule la tvq
    //----------------------------------------
    public function getTvq(){
        $soustotal = $this->getTotal()+FRAIS_LIV+$this->getTps();
        return   $soustotal * 0.09975;
    }
    //--------------------------------------
    //fonction qui calcule le total avec taxes
    //----------------------------------------
    public function getGrandTotal(){
        
        return $this->getTotal() +FRAIS_LIV+ $this->getTps() + $this->getTvq();
    }
    //--------------------------------------
    //fonction qui retourne le nombre de produits differants
    //----------------------------------------
public function getNbProduits(){
   return count($this->panierItems);
   
}
//--------------------------------------
//fonction qui retourne le nombre d'items total
//----------------------------------------
public function getNbItems(){
    $total = 0;
    foreach ($this->panierItems as $item){
        $total += $item->getQuantite();
    }
    return $total;
}
//--------------------------------------
//fonction inutile pour la date de livraison
//----------------------------------------
public function getMinDelDate(){
    $date = new \DateTime();
    $date->add(new \DateInterval('P3D'));
    return $date;
}

//--------------------------------------
//fonction inutile pour la date de livraison
//----------------------------------------
public function getMaxDelDate(){
    $date = new \DateTime();
    $date->add(new \DateInterval('P5D'));
    return $date;
}

}
