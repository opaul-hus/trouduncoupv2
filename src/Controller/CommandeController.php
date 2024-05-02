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
use App\Entity\Commande;
use App\Entity\CommandeDetail;

class CommandeController extends AbstractController
{
    #[Route('/commande', name: 'app_commande')]
    public function index(Request $request): Response
    {
       

        $user = $request->getSession()->get('compte_connecte');
        if($user==null)
        {
            $this->addFlash("erreur", "Vous devez être connecté pour valider votre commande.");
            return $this->redirectToRoute('connexion');
        }

        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    #[Route('/commande/paye', name: 'app_commande_paye')]
        public function pay(Request $request): Response
        {
           
                $ValiderCC = $request->get('ValiderCC');
                if ($ValiderCC != null)
                {
                    return $this->redirectToRoute('app_commande_valider');
                }

            return $this->render('commande/paiment.html.twig', [
                'controller_name' => 'CommandeController',
            ]);

    }   

    #[Route('/commande/valider', name: 'app_commande_valider')]
    public function valider(Request $request,ManagerRegistry $doctrine): Response
    {
        $panier= $request->getSession()->get('panier');
        $user = $request->getSession()->get('compte_connecte');
        $em = $doctrine->getManager();
        $commande = new Commande();
        $commande->setDate(new \DateTime());
        $commande->setCompte($user);
        
        $em->persist($commande);
        $em->flush();
        


        foreach ($panier->getPanierItems() as $panierItem){
            
           
            $produitInventaireQuantity = $em->getRepository(Produits::class)->find($panierItem->getProduit()->getId());

            $commandeDetails=new CommandeDetail();
            $commandeDetails->setProduits($panierItem->getProduit());
            $commandeDetails->setQuantite($panierItem->getQuantite());
            $commandeDetails->setCommande($commande);
             if ( ($panierItem->getQuantite()>$produitInventaireQuantity->getQuanStock())) {
                $commandeDetails->setQunatiteRupture($panierItem->getQuantite()-$produitInventaireQuantity);
                
             }
             $produitInventaireQuantity->setQuanStock($produitInventaireQuantity->getQuanStock()-$panierItem->getQuantite());
             $em->persist($commandeDetails);
             $em->flush();
            


             
        

        }
     
        return $this->redirectToRoute('app_panier_clear');

    

    }
}

