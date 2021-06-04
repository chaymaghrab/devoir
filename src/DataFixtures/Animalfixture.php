<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class Animalfixture extends Fixture
{
    public function load(ObjectManager $manager)
    { 
        
        $faker = Factory::create();
        $user = new User() ;
        $user->setemail('email1');
        $user->setpasword('admin');
        $user->setnom('aa');
        $user->setprenom('nn');
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
