<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContenuPhotoController extends AbstractController
{
    #[Route('/contenu/photo', name: 'app_contenu_photo')]
    public function index(): Response
    {
        return $this->render('compte_pro/index.html.twig', [
            'controller_name' => 'ContenuPhotoController',
        ]);
    }

    #[Route('/new', name: 'app_contenu_photo_id')]   
    public function show(int $id): Response
    {
        return $this->render('compte_pro/index.html.twig', [
            'controller_name' => 'ContenuPhotoController',
        ]);
    }
}
