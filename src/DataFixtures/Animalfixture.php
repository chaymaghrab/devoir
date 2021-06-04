<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Animal;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class Animalfixture extends Fixture
{
    public function load(ObjectManager $manager)
    { for ($i = 1; $i < 10; $i++) {
        $animal = new Animal();
        $animal->setName('nom'.$i);
        $animal->setDescription('desc'.$i);
        $manager->persist($animal);
    }
        
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
