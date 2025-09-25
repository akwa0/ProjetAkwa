<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class RegistrationController extends AbstractController
{
    use TargetPathTrait;
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);


        // recevoir le type d'utilisateur
        $type = $request->get('type');

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            // Après inscription, rester sur le compte correspondant et proposer un bouton Accueil.
            // On force la redirection post-auth vers la page compte (pro ou client) selon le type.
            $routeAfterLogin = ($type === 'pro') ? 'app_compte_pro' : 'app_compte_client';
            $this->saveTargetPath($request->getSession(), 'main', $this->generateUrl($routeAfterLogin));
            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
            'type'=> $type
        ]);
    }

    #[Route('/register/choix/profil', name: 'app_choix_profil_enregistrement')]
    public function registerChoixProfil (){
        return $this->render('registration/choix_profil.html.twig');
    }
}
