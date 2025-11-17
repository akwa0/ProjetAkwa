<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Photo;
use App\Repository\LikeRepository;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/like', name: 'app_like_')]
#[IsGranted('ROLE_USER')]
class LikeController extends AbstractController
{
    #[Route('/photo/{id}/toggle', name: 'toggle_photo', methods: ['POST'])]
    public function toggleLike(
        Photo $photo,
        LikeRepository $likeRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $user = $this->getUser();

        // Vérifier si l'utilisateur a déjà liké cette photo
        $existingLike = $likeRepository->findByPhotoAndUser($photo, $user);

        if ($existingLike) {
            // Supprimer le like
            $entityManager->remove($existingLike);
            $entityManager->flush();
            $liked = false;
        } else {
            // Ajouter un like
            $like = new Like();
            $like->setPhoto($photo);
            $like->setUtilisateur($user);

            $entityManager->persist($like);
            $entityManager->flush();
            $liked = true;
        }

        // Retourner le nombre de likes mis à jour
        $likeCount = $likeRepository->countByPhoto($photo);

        return $this->json([
            'liked' => $liked,
            'likeCount' => $likeCount,
        ]);
    }

    #[Route('/photo/{id}/count', name: 'count_photo', methods: ['GET'])]
    public function countLikes(Photo $photo, LikeRepository $likeRepository): JsonResponse
    {
        $likeCount = $likeRepository->countByPhoto($photo);

        return $this->json([
            'likeCount' => $likeCount,
        ]);
    }

    #[Route('/photo/{id}/check', name: 'check_photo', methods: ['GET'])]
    public function checkLike(Photo $photo, LikeRepository $likeRepository): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['liked' => false]);
        }

        $existingLike = $likeRepository->findByPhotoAndUser($photo, $user);

        return $this->json([
            'liked' => $existingLike !== null,
        ]);
    }
}
