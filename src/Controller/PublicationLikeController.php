<?php

namespace App\Controller;

use App\Repository\LikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/publication/likes', name: 'app_publication_likes')]
#[IsGranted('ROLE_USER')]
class PublicationLikeController extends AbstractController
{
    #[Route('', name: '', methods: ['GET'])]
    public function index(LikeRepository $likeRepository): Response
    {
        $user = $this->getUser();

        // Récupérer tous les likes de l'utilisateur avec les photos associées
        $likes = $likeRepository->findPhotosByUser($user);

        return $this->render('publication_like/index.html.twig', [
            'likes' => $likes,
            'totalLikes' => count($likes),
        ]);
    }
}
