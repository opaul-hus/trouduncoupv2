<?php

namespace App\Controller;

use App\Entity\Compte;
use App\Form\ClientType;
use App\Form\ModifyPasswordType;
use App\Form\LogInType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Response, Request};
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\Persistence\ManagerRegistry;

use App\Classe\NewPassword;
use App\Classe\Util;


class CompteController extends AbstractController
{
    ///--------------------------------------
    //route pour ajouter un compte
    // affiche un formulaire pour ajouter un compte
    // si le formulaire est valide, redirige vers la route compte_possible
    //--------------------------------------
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
                      
                        
                       $request->getSession()->set('client_possible', $compte);

                       return $this->redirectToRoute('compte_possible');
          
                   }
                   else
                   {
                        foreach ($form->getErrors(true) as $error)
                        {
                            $this->addFlash('erreur', $error->getMessage());
                        }
                    }

        }

        return $this->render('ajouterCompte.html.twig', [
            'form' => $form->createView(),
        ]);

    }

    ///--------------------------------------
    //route pour inserer un compte
    // insere le compte dans la base de donnée
    // redirige vers la route acceuilTroupDunCoup
    //--------------------------------------
    #[Route('/insererCompte', name: 'inserer_compte')]
    public function inserer(ManagerRegistry $doctrine,Request $request): Response
    {
        if(Util::Secure($request,'POSSIBLE'))
        {
        $request->getSession()->set('compte_connecte', $request->getSession()->get('client_possible'));
        $em = $doctrine->getManager();
        $em->persist($request->getSession()->get('client_possible'));
        $request->getSession()->remove('client_possible');
        $em->flush();
        $this->addFlash('notice', 'Compte ajouté avec succès , Bienvenue '.$request->getSession()->get('compte_connecte')->getUsername());
        return $this->redirectToRoute('acceuilTroupDunCoup');
        }
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez soumettre un formulaire de nouveau compte pour continuer.');
            return $this->redirectToRoute('acceuilTroupDunCoup');
        }
    }

    ///--------------------------------------
    //route pour afficher un compte possible
    // affiche les informations du compte possible 
    //--------------------------------------
    #[Route('/comptePossible', name: 'compte_possible')]
    public function dossierComptePossible(ManagerRegistry $doctrine,Request $request): Response
    {
        
        if(Util::Secure($request,'POSSIBLE'))
        {
            return $this->render('comptePossible.html.twig');
        }
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez soumettre un formulaire de nouveau compte pour continuer.');
            return $this->redirectToRoute('acceuilTroupDunCoup');
        }

        
    }

    ///--------------------------------------
    //route pour modifier un compte
    // affiche un formulaire pour modifier un compte et un autre modifier le mot de passe
    // si le formulaire est valide, le compte est modifié et la page est rechargée
    //--------------------------------------
    #[Route('/compte', name: 'compte')]
    public function dossierCompte(ManagerRegistry $doctrine,Request $request): Response
    {
        
        if(Util::Secure($request))
        {
           
            $compte = $request->getSession()->get('compte_connecte');
            $form = $this->createForm(ClientType::class, $compte);
            $form->remove('password');
            $form->remove('username');
            $form->handleRequest($request);
            
             $possibleNewPassword = new NewPassword();
             
             $formPassword = $this->createForm(ModifyPasswordType::class, $possibleNewPassword);
             
             $formPassword->handleRequest($request);
            
             
            if ($form->isSubmitted())
            {
                       //Est-ce que les donnée de l'utilsateurs sont valides
                       if ($form->isValid())
                       {
                        
                              $em = $doctrine->getManager();
                              $compteAMod = $em->getRepository(Compte::class)->find($compte->getId());
                                $compteAMod->setNom($compte->getNom());
                                $compteAMod->setPrenom($compte->getPrenom());
                                $compteAMod->setAdresse($compte->getAdresse());
                                $compteAMod->setVille($compte->getVille());
                                $compteAMod->setProvince($compte->getProvince());
                                $compteAMod->setGenre($compte->getGenre());
                                $compteAMod->setCodePostal($compte->getCodePostal());
                                $compteAMod->setNumeroTel($compte->getNumeroTel());
                                $compteAMod->setEmail($compte->getEmail());
                              $em->flush();
                              $this->addFlash('notice', 'Compte modifié avec succès');
                              
              
                       }
                       else
                       {
                            foreach ($form->getErrors(true) as $error)
                            {
                                $this->addFlash('erreur', $error->getMessage());
                            }
                        }
    
            }
    
            if ($formPassword->isSubmitted())
            {
                       //Est-ce que les donnée de l'utilsateurs sont valides
                       if ($formPassword->isValid())
                       {
    
    
                        if ($compte->getPassword() != $possibleNewPassword->getOldPassword()){
                            $this->addFlash('erreur', "Ancien mot de passe incorrect");
                            return $this->render('dossierCompte.html.twig',[
                                'form' => $form->createView(),
                                'formPassword' => $formPassword->createView()
                            ]);
                        }
                        
                              $em = $doctrine->getManager();
                              $compteAMod = $em->getRepository(Compte::class)->find($compte->getId());
                              $compteAMod->setPassword($possibleNewPassword->getNewPassword());
                              $em->flush();
                              $this->addFlash('notice', 'Mot de passe modifié avec succès');
                              
              
                       }
                       else
                       {
                            foreach ($formPassword->getErrors(true) as $error)
                            {
                                $this->addFlash('erreur', $error->getMessage());
                            }
                        }
    
            }
            return $this->render('dossierCompte.html.twig',[
                'form' => $form->createView(),
                'formPassword' => $formPassword->createView()
            ]);
        }
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez vous connecter pour continuer.');
            return $this->redirectToRoute('acceuilTroupDunCoup');
        }

    }
    ///--------------------------------------
    //route pour deconnecter un compte
    // supprime le compte de la session et redirige vers la page d'acceuil
    //--------------------------------------
    #[Route('/deconnexion', name: 'deconnexion')]
    public function deconnexion(ManagerRegistry $doctrine,Request $request): Response
    {
        $this->addFlash('notice', 'Compte deconnecté avec succès , Au revoir '.$request->getSession()->get('compte_connecte')->getUsername());
        $request->getSession()->remove('compte_connecte');
        
        return $this->redirectToRoute('acceuilTroupDunCoup');
    }

    ///--------------------------------------
    //route pour connecter un compte
    // affiche un formulaire pour connecter un compte
    // si le formulaire est valide, le compte est connecté et redirige vers la page d'acceuil
    //--------------------------------------
    #[Route('/connexion', name: 'connexion')]
    public function connexion(ManagerRegistry $doctrine,Request $request): Response
    {
        $compte = new Compte();
        $form = $this->createForm(LogInType::class, $compte);
        $form->handleRequest($request);
        
        if ($form->isSubmitted())
        {

                   //Est-ce que les donnée de l'utilsateurs sont valides
                   if ($form->isValid())
                   {
                       $compte = $form->getData();
                       $compte = $doctrine->getManager()->getRepository(Compte::class)->findOneBy(['username' => $compte->getUsername(), 'password' => $compte->getPassword()]);
                       if ($compte)
                       {
                           $request->getSession()->set('compte_connecte', $compte);
                           $this->addFlash('notice', 'Bienvenue '.$compte->getUsername());
                           return $this->redirectToRoute('acceuilTroupDunCoup');
                       }
                       else
                       {
                           $this->addFlash('erreur', 'Nom d\'utilisateur ou mot de passe incorrect');
                       }
                   }
                   else
                   {
                        foreach ($form->getErrors(true) as $error)
                        {
                            $this->addFlash('erreur', $error->getMessage());
                        }
                    }

        }
        if ($request->getSession()->get('panier_commande')!=null) {
            return $this->redirectToRoute('app_commande');
        }

        return $this->render('connexion.html.twig', [
            'form' => $form->createView(),
        ]);

    }


}
                    
         

