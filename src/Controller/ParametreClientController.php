<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Utilisateur;
use App\Form\UserSettingsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class ParametreClientController extends AbstractController
{
    #[Route('/parametre/client', name: 'app_parametre_client')]
    public function edit(Request $request, EntityManagerInterface $em): Response
    {
       /** @var Utilisateur|null $user */
        $user = $this->getUser();
        if (!$user instanceof Utilisateur) {
            // Not logged in or unexpected user type
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(UserSettingsType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('profilImage')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();
        
                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException) {
                    
                }
        
                // Met à jour le nom de fichier dans l'entité
                $user->setProfilImage($newFilename);
            }
            $em->flush();
            $this->addFlash('success', 'Paramètres modifiés.');
            return $this->redirectToRoute('app_compte_client');
        }
        return $this->render('parametre_client/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
