<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;

use App\Classe\ProduitPanier;
use App\Classe\Panier;

use App\Entity\Produits;

class PanierController extends AbstractController
{
    
    
    #[Route('/panier', name: 'app_panier')]
    public function index(Request $request): Response
    {
        return $this->render('panier.html.twig', [
            'controller_name' => 'PanierController',
        ]);
    }

    #[Route('/panier/add/{id}', name: 'app_panier_add')]
    public function add(ManagerRegistry $doctrine,$id,Request $request): Response
    {
        
       $panier= $request->getSession()->get('panier');
       $panier->add(new ProduitPanier($doctrine,$id,1));
        return $this->redirectToRoute('acceuilTroupDunCoup');
    }

    #[Route('/panier/remove/{id}', name: 'app_panier_remove')]
    public function remove(ManagerRegistry $doctrine,$id,Request $request): Response
    {
        $panier= $request->getSession()->get('panier');
        $panier->remove($doctrine->getManager()->getRepository(Produits::class)->find($id));
        return $this->redirectToRoute('app_panier');
    }

    #[Route('/panier/valider', name: 'app_panier_valider')]
    public function valider(ManagerRegistry $doctrine,Request $request): Response
    {
        $session = $request->getSession();
        $panier = $session->get('panier');
        foreach ($panier->getPanierItems() as $panierItem){
            $fromField='quantity_'.$panierItem->getProduit()->getId();
            $nouvelleQuantite = $request->get($fromField);
            if ($nouvelleQuantite==0) {
                $panier->remove($doctrine->getManager()->getRepository(Produits::class)->find($panierItem->getProduit()->getId())); 
            }
            else{
                $panierItem->setQuantite($nouvelleQuantite);
            }

        }

        return $this->redirectToRoute('app_panier');
    }


    #[Route('/panier/clear', name: 'app_panier_clear')]
    public function clear(Request $request): Response
    {
        $session = $request->getSession();
        $session->set('panier', new Panier());
        return $this->redirectToRoute('app_panier');
    }
  
    

}
