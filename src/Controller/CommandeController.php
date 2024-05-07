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
use App\Entity\Compte;

class CommandeController extends AbstractController
{
    ///--------------------------------------
    //route pour confirmer la commande
    //--------------------------------------
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

    ///--------------------------------------
    //route pour payer la commande
    //--------------------------------------
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

    ///--------------------------------------
    //route pour valider la commande et la stocker dans la base de données
    //--------------------------------------
    #[Route('/commande/valider', name: 'app_commande_valider')]
    public function valider(Request $request,ManagerRegistry $doctrine): Response
    {
        $panier= $request->getSession()->get('panier');
        $compte = $request->getSession()->get('compte_connecte');
        $em = $doctrine->getManager();
        $commande = new Commande();
        
        $commande->setDate(new \DateTime());
        
        $compteAMod = $em->getRepository(Compte::class)->find($compte->getId());
        
        $commande->setClient($compteAMod);
        
        $em->persist($commande);
        $em->flush();
        
        $this->addFlash('notice', 'La commande '.$commande->GetId().' est en préparation!'); 

        foreach ($panier->getPanierItems() as $panierItem){
            
           
            
            $produitAMod = $em->getRepository(Produits::class)->find($panierItem->getProduit()->getId());
            $commandeDetails=new CommandeDetail();
            $commandeDetails->setProduits($produitAMod);
            $commandeDetails->setQuantite($panierItem->getQuantite());
            $commandeDetails->setCommande($commande);
            if ( $panierItem->getQuantite()>$produitAMod->getQuanStock()) {
                if ($produitAMod->getQuanStock()<=0) {
                    $commandeDetails->setQuantiteRupture($panierItem->getQuantite());

                }
                else{
                    $commandeDetails->setQuantiteRupture($panierItem->getQuantite()-$produitAMod->getQuanStock());
                }
                $this->addFlash('warning', 'Attention: rupture de stock pour '.$produitAMod->getNom().' (manque '.$commandeDetails->getQuantiteRupture().' items)'); 
             }
            else{
                    $commandeDetails->setQuantiteRupture(0);
                 }
            
            $produitAMod->setQuanStock($produitAMod->getQuanStock()-$panierItem->getQuantite());
            
            
            $em->persist($commandeDetails);
            $em->flush();    
            
            
            

        

        }
        $session = $request->getSession();
        $session->set('panier', new Panier());
        

        return $this->redirectToRoute('app_commande_details', ['id' => $commande->getId()]);
       

    

    }

    ///--------------------------------------
    //route pour afficher les details d'une commande
    // utiiser quand la commande viens d'etre valider
    //--------------------------------------
    #[Route('/commande/details/{id}', name: 'app_commande_details')]
    public function details(Request $request,ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $commande = $em->getRepository(Commande::class)->find($request->get('id'));

        return $this->render('commande/details.html.twig',
        ['commande' => $commande]);
    }

    ///--------------------------------------
    // route qui demamnde l'annulation d'une commande
    // a un utilisateur de confirmer l'annulation
    //--------------------------------------
    #[Route('/commande/demmandeAnnuler/{id}', name: 'app_commande_demmandeAnnuler')]
    public function demmandeAnnuler(Request $request,ManagerRegistry $doctrine): Response
    {
        $user = $request->getSession()->get('compte_connecte');
        $em = $doctrine->getManager();
        $commande = $em->getRepository(Commande::class)->find($request->get('id'));
        if ($commande->getClient()->getId()!=$user->getId()) {
            $this->addFlash('erreur', 'Vous ne pouvez pas annuler cette commande!'); 
            return $this->redirectToRoute('acceuilTroupDunCoup');
        }
        if ($commande->cancelable()==false) {
            $this->addFlash('notice', 'Vous ne pouvez pas annuler cette commande!'); 
            return $this->redirectToRoute('acceuilTroupDunCoup');
        }
        return $this->render('commande/comfirmAnnuler.html.twig',
        ['commande' => $commande]);
    }

    ///--------------------------------------
    // route pour annuler une commande
    // rajoute les produits de la commande en stock
    // et retire la commande de la base de données
    //--------------------------------------
    #[Route('/commande/annuler/{id}', name: 'app_commande_annuler')]
    public function annuler(Request $request,ManagerRegistry $doctrine): Response
    {
        $user = $request->getSession()->get('compte_connecte');
        $em = $doctrine->getManager();
        $commande = $em->getRepository(Commande::class)->find($request->get('id'));
        if ($commande->getClient()->getId()!=$user->getId()) {
            $this->addFlash('erreur', 'Vous ne pouvez pas annuler cette commande!'); 
            return $this->redirectToRoute('acceuilTroupDunCoup');
        }
        
        if ($commande->cancelable()==false) {
            $this->addFlash('notice', 'Vous ne pouvez pas annuler cette commande!'); 
            return $this->redirectToRoute('acceuilTroupDunCoup');
        }
        foreach ($commande->getCommandeDetail() as $commandeDetail){
            $produitAMod = $em->getRepository(Produits::class)->find($commandeDetail->getProduits()->getId());
            $produitAMod->setQuanStock($produitAMod->getQuanStock()+$commandeDetail->getQuantite());
            
      
            
       
        }
        $em->remove($commande);
        $em->flush();

        return $this->redirectToRoute('commande_historique');
    }
}

