<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Photo;
use App\Entity\Utilisateur;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker;


class PhotoFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            UtilisateurFixtures::class,
            // Add other fixture classes that should be loaded before PhotoFixtures
        ];
    }

    public function load(ObjectManager $manager): void

    {
        $faker =Faker\Factory::create('fr-FR');
        for ($i=0; $i<8 ; $i++){
            $photo = new Photo();
            $photo -> setDateCreation($faker->dateTimeBetween('-10 years', 'now'));
            $photo -> setDescription($faker->paragraph(3));
            //lien avec propriétaire
            $photo -> setAuteur($faker->word(8));

            //lien avec autre user
            $photo->setPhotoPoster($this->getReference("utilisateur".rand(1,5),Utilisateur::class));
            $manager->persist($photo);
        }

        $manager->flush();
    
    }
    
}
