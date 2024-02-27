<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Produits;
use App\Entity\Categories;

class BaseController extends AbstractController
{
    #[Route('/', name: 'acceuilTroupDunCoup')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $tabProduits = $doctrine->
        getManager()->
        getRepository(Produits::class)->
        findAll();

        

        $tabCat = $doctrine->
        getManager()->
        getRepository(Categories::class)->
        findAll();

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
    public function searchBarAction(){
        
    }
}
