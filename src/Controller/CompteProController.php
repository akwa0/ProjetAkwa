<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserSettingsType;

final class CompteProController extends AbstractController
{
    #[Route('/compte/pro', name: 'app_compte_pro')]
    public function index(): Response
    {
 /** @var Utilisateur|null $user */
        $user = $this->getUser();
        if (!$user instanceof Utilisateur) {
            return $this->redirectToRoute('app_login');
        }


        return $this->render('compte_pro/index.html.twig', [
            'user' => $user,
        
        ]);
      
    }
}
