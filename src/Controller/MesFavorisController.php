<?php

namespace App\Controller;

use App\Repository\FavorisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/mes-favoris', name: 'app_mes_favoris')]
#[IsGranted('ROLE_USER')]
class MesFavorisController extends AbstractController
{
    public function __invoke(FavorisRepository $favorisRepository): Response
    {
        $user = $this->getUser();

        // Récupérer tous les artistes favoris de l'utilisateur
        $favoris = $favorisRepository->findByUtilisateur($user);

        return $this->render('mes_favoris/index.html.twig', [
            'favoris' => $favoris,
            'totalFavoris' => count($favoris),
        ]);
    }
}
