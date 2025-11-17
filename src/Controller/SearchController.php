<?php

namespace App\Controller;

use App\Form\SearchProfessionalsType;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SearchController extends AbstractController
{
    #[Route('/recherche-pro', name: 'app_search_professionals')]
    public function searchProfessionals(
        Request $request,
        UtilisateurRepository $utilisateurRepository
    ): Response {
        // Créer le formulaire
        $form = $this->createForm(SearchProfessionalsType::class);
        $form->handleRequest($request);

        $resultats = [];
        $submitted = false;

        // Si le formulaire a été soumis ET valide
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // Appeler la méthode du Repository qu'on a créée
            $resultats = $utilisateurRepository->searchProfessionals(
                $data['nom'] ?? null,
                $data['ville'] ?? null,
                $data['style'] ?? null
            );

            // Stocker les résultats en session
            $request->getSession()->set('search_results', $resultats);
            $request->getSession()->set('search_submitted', true);

            // Rediriger vers la même page
            return new RedirectResponse($this->generateUrl('app_search_professionals'));
        }

        // Récupérer les résultats de la session s'ils existent
        if ($request->getSession()->has('search_results')) {
            $resultats = $request->getSession()->get('search_results');
            $submitted = $request->getSession()->get('search_submitted', false);
        }

        // Envoyer le formulaire ET les résultats au template
        return $this->render('search/search_professionals.html.twig', [
            'form' => $form->createView(),
            'resultats' => $resultats,
            'submitted' => $submitted,
        ]);
    }
}