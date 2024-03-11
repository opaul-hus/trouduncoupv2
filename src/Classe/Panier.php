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
    private function contientProduit(ProduitPanier $produit){
        foreach ($this->panierItems as $item){
            if ($item->getProduit()->getId() == $produit->getProduit()->getId()){
                return true;
            }
        }
        return false;
  
    }



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

    public function remove(Produits $produit){
        foreach ($this->panierItems as $item){
            if ($item->getProduit()->getId() == $produit->getId()){
                $key = array_search($item, $this->panierItems);
                unset($this->panierItems[$key]);
            }
        }
    }

    public function getPanierItems(){
  
        return $this->panierItems;
    }   

    

    public function getTotal(){
        $total = 0.00;
        foreach ($this->panierItems as $item){
            $total += $item->getProduit()->getPrix() * $item->getQuantite();
        }
        return $total;
    }


    public function clear(){
        $this->panierItems = [];
    }

    public function getTps(){
        $soustotal = $this->getTotal()+FRAIS_LIV;
        return  $soustotal * 0.05;
    }

    public function getTvq(){
        $soustotal = $this->getTotal()+FRAIS_LIV+$this->getTps();
        return   $soustotal * 0.09975;
    }
    public function getGrandTotal(){
        
        return $this->getTotal() +FRAIS_LIV+ $this->getTps() + $this->getTvq();
    }
public function getNbProduits(){
   return count($this->panierItems);
   
}

public function getNbItems(){
    $total = 0;
    foreach ($this->panierItems as $item){
        $total += $item->getQuantite();
    }
    return $total;
}

public function getMinDelDate(){
    $date = new \DateTime();
    $date->add(new \DateInterval('P3D'));
    return $date;
}


public function getMaxDelDate(){
    $date = new \DateTime();
    $date->add(new \DateInterval('P5D'));
    return $date;
}

}
