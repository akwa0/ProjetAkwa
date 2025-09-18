<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Message;
use App\Entity\Utilisateur;
use Faker;
class MessageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    { $faker =Faker\Factory::create('fr-FR');
        for ($i=0; $i<3 ; $i++){
            $message = new Message();
            $message -> setDateEnvoie($faker->dateTimeBetween('-10 years', 'now'));
            $message -> setContenu($faker->paragraph(10));
            $message -> setEtat($faker->word(3));
            //$unUtilisateur=$this->getReference("utilisateur".rand(0,2), Utilisateur);
            //$message->setUtilisateur($unUtilisateur);
    
            $manager->persist($message);
        }

        $manager->flush();
    }
}
