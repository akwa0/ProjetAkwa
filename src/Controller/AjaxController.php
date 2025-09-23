<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class AjaxController extends AbstractController
{
    #[Route('/ajax/form/independant', name: 'app_ajax')]
    public function formIndependant(): Response
    {
        return $this->render('ajax/form_independant.html.twig');
    }

    #[Route('/ajax/form/independant/traitement', name: 'app_ajax_form_independant_traitement')]
    public function formIndependantTraitement(Request $req): Response
    {   $nom=$req->get('nom');
        $vars=[
            'message'=>'Bonjour '. $nom,
            'autreDonnee'=>'Donnée supplémentaire',
            'status'=>'success',
        ];
        return new JsonResponse($vars);
    
    }
}
