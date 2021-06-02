<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     */
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }


    /**
     * @Route("/categorie/add/{name}", name="categorie.add")
     */
    public function add($name): Response
    {
        $categorie= new categorie();
        $categorie->setName($name);
        $entityManager=$this->getDoctrine()->getManager();

        $entityManager->persist($categorie);
        $entityManager->flush();
        $this->addFlash("notice", "Produit ajouté avec succés..");
        //return $this->redirectToRoute("produit.list");
       return $this->render('categorie/add.html.twig', [
            'categorie' => $categorie,
        ]);
    }
}
