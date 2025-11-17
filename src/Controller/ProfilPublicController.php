<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilPublicController extends AbstractController
{
    #[Route('/professionnel/{id}', name: 'app_profil_public', requirements: ['id' => '\d+'])]
    public function show(int $id, UtilisateurRepository $utilisateurRepository): Response
    {
        // Récupérer l'utilisateur (professionnel)
        $professionnel = $utilisateurRepository->find($id);

        // Vérifier que l'utilisateur existe
        if (!$professionnel) {
            throw $this->createNotFoundException('Le professionnel n\'existe pas.');
        }

        // Vérifier que c'est bien un professionnel (a le rôle ROLE_ADMIN)
        if (!in_array('ROLE_ADMIN', $professionnel->getRoles(), true)) {
            throw $this->createNotFoundException('Cet utilisateur n\'est pas un professionnel.');
        }

        return $this->render('profil_public/index.html.twig', [
            'professionnel' => $professionnel,
        ]);
    }
}
