<?php

namespace App\Controller;

use App\Entity\Animal;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnimalController extends AbstractController
{/**
     * @Route("/animallist", name="animal.list" )
     */
    public function list(): Response
    {
        $repository= $this->getDoctrine()->getRepository(Animal::class);
        $animal=$repository->findAll();
        return $this->render('animal/list.html.twig', [
            'animal' =>  $animal ,
        ]);
    }
    /**
     * @Route("/animal/detail/{id}", name="animal.detail")
     */
    public function detail(Animal $animal): Response
    {
       // $repository = $this->getDoctrine()->getRepository(Produit::class);
       // $produit = $repository->find($id);
        if (!$animal ){
            throw $this->createNotFoundException(
                'Aucun animal trouvé'
            );
        }

        return $this->render('animal/detail.html.twig', [
            'animal' => $animal,
        ]);
    }
    /**
     * @Route("/animal/delete/{id}", name="animal.delete")
     */
    public function delete($id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Animal::class);
        $animal = $repository->find($id);
        if (!$animal) {
            throw $this->createNotFoundException(
                'Aucun animal ayant le id : ' . $id
            );
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($animal);
        $entityManager->flush();
        $this->addFlash("notice","animal supprimé avec succés..");

        return $this->redirectToRoute("animal.list");

    }

    /**
     * @Route("/animal/add/{name}/{desc}", name="animal.add")
     */
    public function add($name,$desc): Response
    {
        $animal= new Animal();
        $animal->setName($name);
        $animal->setDescription($desc);

        $entityManager=$this->getDoctrine()->getManager();

        $entityManager->persist($animal);
        $entityManager->flush();
        $this->addFlash("notice", "animal ajouté avec succés..");

      return $this->render('animal/add.html.twig', [
            'animal' => $animal,
        ]);
    }

    /**
     * @Route("/addanimal", name="animal.addanimal")
     */
    public function addanimal(Request $request): Response
    {
        // création de l'entité
        $animal = new Animal();
        // création du formulaire lié à l'entité
        $form = $this->createForm(ProduitType::class,$animal);
        //Traitement du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $animal = $form->getData();
            $animal->setCreatedAt(new \DateTime());
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($animal);
             $entityManager->flush();

            return $this->redirectToRoute('animal.list');
        }

        return $this->render('animal/addanimal.html.twig',['form' => $form->createView()]);
    }
}
