<?php

namespace App\Controller;

use App\Entity\Animal;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnimalController extends AbstractController
{
    /**
     * @Route("/animal", name="animal")
     */
    public function test(): Response
    {
        return $this->render('animal/test.html.twig', [
            'controller_name' => 'AnimalController',
        ]);
    }

    /**
     * @Route("/animal/add/{name}/{desc}", name="animal.add")
     */
    public function add($name,$desc): Response
    {
        $animal= new Animal();
        $animal->setName($name);
        //$animal->setAge($age);
        $animal->setDescription($desc);
        //$animal->setCategorie($categ);

        $entityManager=$this->getDoctrine()->getManager();

        $entityManager->persist($animal);
        $entityManager->flush();
        $this->addFlash("notice", "animal ajouté avec succés..");

      return $this->render('animal/add.html.twig', [
            'animal' => $animal,
        ]);
    }
}
