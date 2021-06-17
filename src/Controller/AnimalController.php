<?php

namespace App\Controller;

use App\Entity\Animal;
use App\Form\AnimalType;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\AnimalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnimalController extends AbstractController
{/**
     * @Route("/list", name="animal.list" )
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
     * @Route("/", name="animal.home" )
     */
    public function home(): Response
    {
        $repository= $this->getDoctrine()->getRepository(Animal::class);
        $animal=$repository->findAll();
        return $this->render('animal/home.html.twig', [
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
     * @Route("/animal/update/{id}/{newname}", name="animal.update")
     */
    public function update($id,$newname): Response
    {
        // chercher le produit à modifier
        $repository = $this->getDoctrine()->getRepository(Animal::class);
        $animal = $repository->find($id);

        // on modifie le produit
        $animal->setName($newname);
        
        $entityManager = $this->getDoctrine()->getManager();
        // dans ce cas le persist n'est pas obligatoire
        $entityManager->persist($animal);
        $entityManager->flush();
        $this->addFlash("notice", "Produit modifié avec succés..");
        return $this->redirectToRoute("animal.list");
       
    }

    /**
     * @Route("/addanimal", name="animal.addanimal")
     */
    public function addanimal(Request $request,SluggerInterface $slugger): Response
    {
        // création de l'entité
        $animal = new Animal();
        // création du formulaire lié à l'entité
        $form = $this->createForm(AnimalType::class,$animal);
        //Traitement du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $animal = $form->getData();
             /**@var UploadedFile $uploadedFile */
             $uploadedFile = $form['imageFile']->getData();
             $destination = $this->getParameter('kernel.project_dir').'/public/uploads';
             //$destination = $this->getParameter('kernel.project_dir').'/templates/proteine';
             $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
             $safeFilename = $slugger->slug($originalFilename);
             $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
             try {
                 $uploadedFile->move(
                     $destination,
                     $newFilename
                 );
             } catch (FileException $e) {
                 // ... handle exception if something happens during file upload
             }
             $animal->setFileName($newFilename);
            //$animal->setCreatedAt(new \DateTime());
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($animal);
             $entityManager->flush();
             $this->addFlash("notice","animal ajouter avec succés..");
            return $this->redirectToRoute('animal.list');
        }

        return $this->render('animal/addanimal.html.twig',['form' => $form->createView()]);
    }

     /**
     * @Route("edit/{id}", name="animal.edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Animal $animal): Response
    {
        $form = $this->createForm(AnimalType::class, $animal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('animal.list');
        }

        return $this->render('animal/edit.html.twig', [
            'animal' => $animal,
            'form' => $form->createView(),
        ]);
    }
    /**
     * @Route("/recherche", name="recherche")
     * 
     */
    public  function Rechercher(Request $request){
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class,$propertySearch);
        $form->handleRequest($request);
        $animals=[];
        if ($form->isSubmitted()&&$form->isValid()){

        $nom = $propertySearch->getNom();
        if ($nom!= "")
        //si on a fourni un nom d'article on affiche tous les articles ayan
        $animals=$this->getDoctrine()->getRepository(Animal::class)->findBy(['Name'=>$nom]);
        else
        //si si aucun nom n'est fourni on affiche tous les articles
        $animals= $this->getDoctrine()->getRepository(Animal::class)->findAll();
        }

        return $this ->render('animal/recherche.html.twig',['form'=>$form->createView(),'animals'=>$animals]);
        }
        
}
