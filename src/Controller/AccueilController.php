<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

//importer les entities
use App\Entity\Photo;
use App\Entity\Utilisateur;

final class AccueilController extends AbstractController
{
    // 

    #[Route('/', name: 'app_accueil')]
    public function index (): Response
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();
        // Utilisateur connecté
        if ($user instanceof Utilisateur) {
            
            // dd($user);
        
            // si le user est pro
            if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
                return $this->redirectToRoute('app_compte_pro');
            }
            // sinon client
            return $this->redirectToRoute('app_compte_client');
        }

        // pas d'user, afficher la page d'accueil publique
        return $this->render('accueil/index.html.twig');
    }
}