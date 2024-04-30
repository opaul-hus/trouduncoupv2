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
        $panier= $request->get('panier');
        $request->getSession()->set('panier_commande',$panier );

        if($request->getSession()->get('compte_connecte')==null)
        {
            $this->addFlash("notice", "Vous devez être connecté pour valider votre commande.");
            return $this->redirectToRoute('connexion');
        }

        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }
}
