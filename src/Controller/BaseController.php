<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Produits;
use App\Entity\Categories;

class BaseController extends AbstractController
{
    #[Route('/', name: 'acceuilTroupDunCoup')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        $tabProduits = $doctrine->
        getManager()->
        getRepository(Produits::class)->
        findAll();

        

        $tabCat = $doctrine->
        getManager()->
        getRepository(Categories::class)->
        findAll();

                // Récup à partir du $_GET                            
                $categorie = $request->query->get('categorie');
                //dd($texteRecherche);
        
                if (isset($categorie))
                {
                    $tabProduits = $this->AppliqueCritere($tabProduits, $categorie);
                    if (count($tabProduits) == 0)
                    {
                        $this->addFlash("notice", "Aucun produit ne correspond à votre recherche");
                    }
                }


                                
            
    
        
        

        return $this->render('accueil.html.twig',
         ['tabProduits' => $tabProduits, 'tabCat' => $tabCat]);
    }
    #[Route('/recherche', name: 'rechercheTroupDunCoup')]
    public function rechercheTroupDunCoup(ManagerRegistry $doctrine, Request $request): Response
    {
        $tabProduits = $doctrine->
        getManager()->
        getRepository(Produits::class)->
        findAll();

        

        $tabCat = $doctrine->
        getManager()->
        getRepository(Categories::class)->
        findAll();

              
        
                if (isset($categorie))
                {
                    $tabProduits = $this->AppliqueCritere($tabProduits, $categorie);
                    if (count($tabProduits) == 0)
                    {
                        $this->addFlash("notice", "Aucun produit ne correspond à votre recherche");
                    }
                }            
         
            $texteRecherche = $request->get('searchField');

            
                
                if (strlen($texteRecherche) > 0 )
                {
                    $tabProduits = $this->RechercheBar($tabProduits, strtolower($texteRecherche));
                    if (count($tabProduits) == 0)
                    {
                        $this->addFlash("notice", "Aucun produit n'a un nom ou description contenant '$texteRecherche'");
                    }
                }
                else {
                    $this->addFlash("notice", "Veuillez remplir le champ de recherche");                
                }
            
    
        
        

        return $this->render('accueil.html.twig',
         ['tabProduits' => $tabProduits, 'tabCat' => $tabCat]);
    }


    #[Route('/produit/details/{id}', name:'produit_details')]
    public function produits_details(ManagerRegistry $doctrine, $id): Response
    {
       $em = $doctrine->getManager();

       $produit = $em->getRepository(Produits::class)->find($id);
       

       

       return $this->render("detailsProduit.html.twig", ['produit' => $produit]);

    }
    #[Route('/a_propos', name:'produit_ajout')]
    public function about_us(ManagerRegistry $doctrine, Request $request): Response
    {

        return $this->render('aboutUs.html.twig', []);
    }

    //--------------------------------------
    //
    //--------------------------------------
    private function AppliqueCritere($tabProduits, $crit)
    {
        $tabTmp = [];
        foreach($tabProduits as $unProduits)
        {
            if ( $unProduits->getCategorie()->getId()==$crit)
            {
                $tabTmp[] = $unProduits;
            }

        }
        return $tabTmp;
    }
    //--------------------------------------
    //
    //--------------------------------------
    private function RechercheBar($tabProduits, $crit)
    {
        $tabTmp = [];
         foreach($tabProduits as $unProduits)
         {
             if ( strpos( strtolower($unProduits->getNom()), $crit) !== false)
             {
                $tabTmp[] = $unProduits;
             }
             else if ( strpos( strtolower($unProduits->getDescription()), $crit) !== false)
                 $tabTmp[] = $unProduits;
             {
           }

         }
        return $tabTmp;
    }

}
