<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Classe\ProduitPanier;
use App\Classe\Panier;

use App\Entity\Produits;

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
    public function valider(Request $request): Response
    {
        $panier= $request->getSession()->get('panier');
        $user = $request->getSession()->get('compte_connecte');
        $em = $this->getDoctrine()->getManager();
        $commande = new Commande();
        $commande->setDateCommande(new \DateTime());
        $commande->setCompte($user);
        $commande->setDetailCommande($panier->getProduits());


    }
}

