<?php

namespace App\Controller;

use App\Form\UserSettingsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ParametreController extends AbstractController
{
    #[Route('/parametre', name: 'app_parametre')]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserSettingsType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Paramètres modifiés.');
            return $this->redirectToRoute('app_parametre');
        }
        return $this->render('parametre/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
