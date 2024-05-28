<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


use Symfony\Component\HttpFoundation\{Response, Request};
use Symfony\Component\Routing\Attribute\Route;

use App\Classe\Util;
use App\Classe\PhotoProduit;

use App\Form\NewCatType;
use App\Form\ModAllCatType;
use App\Form\AddProductType;
use App\Form\PhotoType;

use App\Classe\CategoriesList;

use Doctrine\Persistence\ManagerRegistry;

use App\Entity\Categories;
use App\Entity\Produits;



class AdminController extends AbstractController
{
    #[Route('/admin', name: 'controle_admin')]
    public function index(ManagerRegistry $doctrine,Request $request): Response
    {
  

        if(Util::Secure($request)&&$request->getSession()->get('compte_connecte')->getUsername()=="admin")
        {

            return $this->render('admin/index.html.twig', [
                'controller_name' => 'AdminController',
            ]);
        }
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez vous connecter pour continuer.');
            return $this->redirectToRoute('connexionAdmin');
        }

    }

    #[Route('/admin/ajouterCat', name: 'add_cat')]
    public function addCat(ManagerRegistry $doctrine,Request $request): Response
    {
        if(Util::Secure($request)&&$request->getSession()->get('compte_connecte')->getUsername()=="admin")
        {
            $em = $doctrine->getManager();
            $categories=$em->getRepository(Categories::class)->findAll();

            $categorie = new Categories();
            $form = $this->createForm(NewCatType::class, $categorie);
            $form->handleRequest($request);
            
            if ($form->isSubmitted())
            {
    
                       //Est-ce que les donnée de l'utilsateurs sont valides
                       if ($form->isValid())
                       {
                           
                            $em->persist($categorie);
                            $em->flush();
                            $this->addFlash('notice', 'Catégorie ajoutée avec succès. ');
                           return $this->redirectToRoute('controle_admin');
              
                       }
                       else
                       {
                            foreach ($form->getErrors(true) as $error)
                            {
                                $this->addFlash('erreur', $error->getMessage());
                            }
                        }
    
            }
    
            return $this->render('admin/ajouterCat.html.twig', [
                'form' => $form->createView(),
                'categories' => $categories
            ]);
    
        }
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez vous connecter pour continuer.');
            return $this->redirectToRoute('connexionAdmin');
        }

    }

    #[Route('/admin/ajouterProd', name: 'add_prod')]
    public function addProd(ManagerRegistry $doctrine,Request $request): Response
    {
        $em = $doctrine->getManager();
        $categories=$em->getRepository(Categories::class)->findAll();

        $produit = new Produits();
        $form = $this->createForm(AddProductType::class, $produit);
        $form->handleRequest($request);

        if(Util::Secure($request)&&$request->getSession()->get('compte_connecte')->getUsername()=="admin")
        {

            if ($form->isSubmitted())
            {
    
                       //Est-ce que les donnée de l'utilsateurs sont valides
                       if ($form->isValid())
                       {
                           
                            $em->persist($produit);
                            $em->flush();
                            $this->addFlash('notice', 'produit ajoutée avec succès. ');
                           return $this->redirectToRoute('controle_admin');
              
                       }
                       else
                       {
                            foreach ($form->getErrors(true) as $error)
                            {
                                $this->addFlash('erreur', $error->getMessage());
                            }
                        }
    
            }
    
            return $this->render('admin/ajouterProd.html.twig', [
                'controller_name' => 'AdminController',
                'form' => $form->createView(),
            ]);
        }
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez vous connecter pour continuer.');
            return $this->redirectToRoute('connexionAdmin');
        }

    }
    #[Route('/admin/modifierCat', name: 'edit_cat')]
    public function editCat(ManagerRegistry $doctrine,Request $request): Response
    {
  


        
      
        if(Util::Secure($request)&&$request->getSession()->get('compte_connecte')->getUsername()=="admin")
        {
      
            $em = $doctrine->getManager();
            $categories=$em->getRepository(Categories::class)->findAll();
            $catsList = new CategoriesList();
            $catsList->setCategories($categories);
            $form=$this->createForm(ModAllCatType::class,$catsList);
            $form->handleRequest($request);
            
                if ($form->isSubmitted()){

                        
                           //Est-ce que les donnée de l'utilsateurs sont valides
                           if ($form->isValid())
                           {
                               
                                foreach ($catsList->getCategories() as $cat)
                                {
                                    $em->persist($cat);
                                    $em->flush();

                                }
                                $this->addFlash('notice', 'Catégories modifiées avec succès. ');
                                return $this->redirectToRoute('controle_admin');
                  
                           }
                           else
                           {
                                foreach ($form->getErrors(true) as $error)
                                {
                                    dd($error->getMessage());
                                    $this->addFlash('erreur', $error->getMessage());
                                }
                            }
        


                }
                
                return $this->render('admin/modCat.html.twig', [
                    'form' => $form->createView(),
                    'categories' => $categories
                ]);

                }
                
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez vous connecter pour continuer.');
            return $this->redirectToRoute('connexionAdmin');
        }

    }

    #[Route('/admin/modifierProd', name: 'edit_prod')]
    public function editProds(ManagerRegistry $doctrine,Request $request): Response
    {
        $em = $doctrine->getManager();
        $produits=$em->getRepository(Produits::class)->findAll();

        if(Util::Secure($request)&&$request->getSession()->get('compte_connecte')->getUsername()=="admin")
        {
            return $this->render('admin/listProd.html.twig', [
                'controller_name' => 'AdminController',
                'produits' => $produits
            ]);
        }
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez vous connecter pour continuer.');
            return $this->redirectToRoute('connexionAdmin');
        }

    }


    #[Route('/admin/produit/{id}', name: 'produit_admin')]
    public function editProd(ManagerRegistry $doctrine,Request $request,$id): Response
    {
        $em = $doctrine->getManager();
        $produit=$em->getRepository(Produits::class)->find($id);
        $form = $this->createForm(AddProductType::class, $produit);
        $form->handleRequest($request);


        if(Util::Secure($request)&&$request->getSession()->get('compte_connecte')->getUsername()=="admin")
        {

            if ($form->isSubmitted())
            {
    
                       //Est-ce que les donnée de l'utilsateurs sont valides
                       if ($form->isValid())
                       {
                           
                            $em->persist($produit);
                            $em->flush();
                            $this->addFlash('notice', 'produit modifié avec succès. ');
                           return $this->redirectToRoute('controle_admin');
              
                       }
                       else
                       {
                            foreach ($form->getErrors(true) as $error)
                            {
                                $this->addFlash('erreur', $error->getMessage());
                            }
                        }
    
            }
    
            return $this->render('admin/modProd.html.twig', [
                'controller_name' => 'AdminController',
                'form' => $form->createView(),
                'produit' => $produit
            ]);
        }
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez vous connecter pour continuer.');
            return $this->redirectToRoute('connexionAdmin');
        }

    }

    #[Route('/admin/produit/{id}/photo/', name: 'modifier_photo')]
    public function editPhoto(ManagerRegistry $doctrine,Request $request,$id): Response
    {
        $principale = $request->query->get('principale');
        
        if(Util::Secure($request)&&$request->getSession()->get('compte_connecte')->getUsername()=="admin")
        {
           
        $em = $doctrine->getManager();
        $produit=$em->getRepository(Produits::class)->find($id);
      
        if ($principale)
        {
            $nomFichierImage = __DIR__ . '/../../public/css/images/' . $produit->getId() . '.png';
        }
        else
        {
            $nomFichierImage = __DIR__ . '/../../public/css/images/' . $produit->getId() . '_2.png';

        }


        
           //Le fichier image existe t il?
           //dd($nomFichierImage);
           if (file_exists($nomFichierImage))
            {
                if ($principale)
                {
                    $image =  'images/' . $produit->getId() . '.png';
                }
                else
                {
                    $image =  'images/' . $produit->getId() . '_2.png';
                }
            }
            else
            {
               
                $image =  'images/pasImage.png';
            }

            $photoProduit = new PhotoProduit();
            $photoProduit->setProduitId($produit->getId());

            $formPhoto = $this->createForm(PhotoType::class, $photoProduit);

            $formPhoto->HandleRequest($request);
            if ($formPhoto->issubmitted())
            {
                if ($formPhoto->isValid())
                {
                    $codeErreur = 0;
                    if ($photoProduit->televerse($codeErreur, $principale))
                    {
                        $this->addFlash("succes", "Image du produit téléversée avec succès!!");
                    }
                    else
                    {
                        $this->addFlash("erreur", "Erreur de téléversement : $codeErreur ...");
                    }
                }
                else
                {
                    $this->addFlash("erreur", "Fichier invalide... ");

                }

   

    
            }

    
            return $this->render('admin/modPhoto.html.twig', [
                'controller_name' => 'AdminController',
                'form' => $formPhoto->createView(),
                'image' => $image,
                'produit' => $produit,
                'principale' => $principale
            ]);
        }
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez vous connecter pour continuer.');
            return $this->redirectToRoute('connexionAdmin');
        }

    }




    #[Route('/admin/rapVentes', name: 'rap_ventes')]
    public function rapVentes(ManagerRegistry $doctrine,Request $request): Response
    {
        if(Util::Secure($request)&&$request->getSession()->get('compte_connecte')->getUsername()=="admin")
        {
            return $this->render('admin/index.html.twig', [
                'controller_name' => 'AdminController',
            ]);
        }
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez vous connecter pour continuer.');
            return $this->redirectToRoute('connexionAdmin');
        }

    }

    
    #[Route('/admin/list_prod', name: 'list_prod')]
    public function listeProd(ManagerRegistry $doctrine,Request $request): Response
    {
        if(Util::Secure($request)&&$request->getSession()->get('compte_connecte')->getUsername()=="admin")
        {
            return $this->render('admin/index.html.twig', [
                'controller_name' => 'AdminController',
            ]);
        }
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez vous connecter pour continuer.');
            return $this->redirectToRoute('connexionAdmin');
        }

    }

    #[Route('/admin/oosProd', name: 'oos_product')]
    public function oosProd(ManagerRegistry $doctrine,Request $request): Response
    {
        if(Util::Secure($request)&&$request->getSession()->get('compte_connecte')->getUsername()=="admin")
        {
            return $this->render('admin/index.html.twig', [
                'controller_name' => 'AdminController',
            ]);
        }
        else
        {
            $this->addFlash('notice', 'Tetative de fraude détectée, veuillez vous connecter pour continuer.');
            return $this->redirectToRoute('connexionAdmin');
        }

    }
    

    



    

}
