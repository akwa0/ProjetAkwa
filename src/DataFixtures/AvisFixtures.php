<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Avis;
use Faker;

class AvisFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {$faker =Faker\Factory::create('fr-FR');
        for ($i=0; $i<2 ; $i++){
            $avis = new Avis();
            $avis -> setNombreEtoile($faker->randomDigit());
            $avis -> setNombreCommentaire($faker->randomDigit());
            $avis -> setdateCreation($faker->dateTimeBetween('-10 years', 'now'));
    
            $manager->persist($avis);
        }


        $manager->flush();
    }
}
