<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Utilisateur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UtilisateurFixtures extends Fixture
{
    //on peut importer directecment seulement dans controller 
    private $hasher;
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }


    public function load(ObjectManager $manager): void
    {  
        $faker = Factory::create('fr_FR');
        
        for ($i = 0; $i<=5; $i++){
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($faker->lastName);
            $utilisateur->setDateNaissance($faker->dateTimeBetween('-70 years', '-18 years'));
            $utilisateur->setEmail('user'.$i.'@gmail.com');
            $utilisateur->setPassword($this->hasher->hashPassword($utilisateur,'password'.$i));
            $utilisateur ->setRoles(['ROLE_USER']);
            $manager->persist($utilisateur);

            $this ->addReference("utilisateur".$i,$utilisateur);

            
        }
        for ($i = 0; $i<=5; $i++){
            $utilisateur = new Utilisateur();
            $utilisateur->setNom($faker->lastName);
            $utilisateur->setDateNaissance($faker->dateTimeBetween('-70 years', '-18 years'));
            $utilisateur->setEmail('admin'.$i.'@gmail.com');
            $utilisateur->setPassword($this->hasher->hashPassword($utilisateur,'password'.$i));
            $utilisateur ->setRoles(['ROLE_ADMIN']);
            $manager->persist($utilisateur);

            $this->addReference("admin".$i,$utilisateur); 
        
        }
        

        $manager->flush();
    }
}
