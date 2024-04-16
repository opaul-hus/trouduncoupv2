<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Form\ClientType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response, Request};
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\Persistence\ManagerRegistry;


class CompteController extends AbstractController
{
    #[Route('/ajouterCompte', name: 'app_cree_controler')]
    public function index(ManagerRegistry $doctrine,Request $request): Response
    {


        $compte = new Compte();
        $form = $this->createForm(ClientType::class, $compte);
        $form->handleRequest($request);
        
        if ($form->isSubmitted())
        {

                   //Est-ce que les donnée de l'utilsateurs sont valides
                   if ($form->isValid())
                   {
                       $em = $doctrine->getManager();
                       $em->persist($compte);
                       $em->flush();
       
                       $request->getSession()->set('compte_connecte', $compte);
                       $this->addFlash('succes', "Compte " . $compte->getNom() . " créé avec succès");
                       return $this->redirectToRoute('dossier_compte');
                   }
                   else
                   {
                        // Les données contiennent des erreurs
                        $this->addFlash('erreur', "Au moins une erreur dans les données fournies");
                    }

        }

        return $this->render('ajouterCompte.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    #[Route('/dossierCompte', name: 'dossier_compte')]
    public function dossierCompte(ManagerRegistry $doctrine,Request $request): Response
    {
        return $this->render('dossierCompte.html.twig');
    }


}
                    
         

