<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Photo;
use Faker;

class PhotoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void

    {
        $faker =Faker\Factory::create('fr-FR');
        for ($i=0; $i<5 ; $i++){
            $photo = new Photo();
            $photo -> setDateCreation($faker->dateTimeBetween('-10 years', 'now'));
            $photo -> setDescription($faker->paragraph(3));
            $photo -> setAuteur($faker->word(8));
    
            $manager->persist($photo);
        }

        $manager->flush();
    }
}
