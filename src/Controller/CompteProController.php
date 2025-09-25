<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CompteProController extends AbstractController
{
    #[Route('/compte/pro', name: 'app_compte_pro')]
    public function index(): Response
    {
        return $this->render('compte_pro/index.html.twig', [
            'controller_name' => 'CompteProController',
        ]);
    }
}
