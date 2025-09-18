<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Utilisateur;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
        for ($i = 0; $i<=5; $i++){
            $utilisateur = new Utilisateur();
            //$utilisateur->setNom('nom'.$i);
            //$utilisateur->setPrenom('prenom'.$i);
            $utilisateur->setEmail('email'.$i.'@gmail.com');
            $utilisateur->setPassword($this->hasher->hashPassword($utilisateur,'password'.$i));
            //$utilisateur ->setRoles();
            $manager->persist($utilisateur);
        }
        

        $manager->flush();
    }
}
