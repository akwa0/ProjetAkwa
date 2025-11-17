<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Entity\Utilisateur;
use App\Repository\FavorisRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/favoris', name: 'app_favoris_')]
#[IsGranted('ROLE_USER')]
class FavorisController extends AbstractController
{
    #[Route('/artiste/{id}/toggle', name: 'toggle_artiste', methods: ['POST'])]
    public function toggleFavoris(
        Utilisateur $artiste,
        FavorisRepository $favorisRepository,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $user = $this->getUser();

        // Vérifier que c'est bien un artiste (ROLE_ADMIN)
        if (!in_array('ROLE_ADMIN', $artiste->getRoles(), true)) {
            return $this->json(['error' => 'Cet utilisateur n\'est pas un artiste'], 400);
        }

        // Vérifier que l'utilisateur ne met pas lui-même en favori
        if ($user->getId() === $artiste->getId()) {
            return $this->json(['error' => 'Vous ne pouvez pas vous mettre en favori'], 400);
        }

        // Vérifier si l'utilisateur a déjà mis en favori cet artiste
        $existingFavoris = $favorisRepository->findByUtilisateurAndArtiste($user, $artiste);

        if ($existingFavoris) {
            // Supprimer le favori
            $entityManager->remove($existingFavoris);
            $entityManager->flush();
            $favoris = false;
        } else {
            // Ajouter un favori
            $favoris = new Favoris();
            $favoris->setUtilisateur($user);
            $favoris->setArtiste($artiste);

            $entityManager->persist($favoris);
            $entityManager->flush();
            $favoris = true;
        }

        // Retourner le nombre de favoris de l'artiste
        $favoriCount = $favorisRepository->countByArtiste($artiste);

        return $this->json([
            'favoris' => $favoris,
            'favoriCount' => $favoriCount,
        ]);
    }

    #[Route('/artiste/{id}/count', name: 'count_artiste', methods: ['GET'])]
    public function countFavoris(Utilisateur $artiste, FavorisRepository $favorisRepository): JsonResponse
    {
        $favoriCount = $favorisRepository->countByArtiste($artiste);

        return $this->json([
            'favoriCount' => $favoriCount,
        ]);
    }

    #[Route('/artiste/{id}/check', name: 'check_artiste', methods: ['GET'])]
    public function checkFavoris(Utilisateur $artiste, FavorisRepository $favorisRepository): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['favoris' => false]);
        }

        $existingFavoris = $favorisRepository->findByUtilisateurAndArtiste($user, $artiste);

        return $this->json([
            'favoris' => $existingFavoris !== null,
        ]);
    }
}
