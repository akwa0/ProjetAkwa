<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Utilisateur;
use App\Form\UserSettingsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class ContenuPhotoController extends AbstractController
{
    #[Route('/contenu/photo', name: 'app_contenu_photo')]
    public function addPhoto(Request $request, EntityManagerInterface $em): Response
    {
      /** @var Utilisateur|null $user */
      $user = $this->getUser();
      if (!$user instanceof Utilisateur) {
          // Not logged in or unexpected user type
          return $this->redirectToRoute('app_login');
      }
      $photo = new Photo();
      $form = $this->createForm(PhotoType::class, $photo);
      $form->handleRequest($request);

    
      
      if ($form->isSubmitted() && $form->isValid()) {
          $imageFile = $form->get('photo')->getData();

          if ($imageFile) {
              $newFilename = uniqid().'.'.$imageFile->guessExtension();
      
              try {
                  $imageFile->move(
                      $this->getParameter('uploads_directory'),
                      $newFilename
                  );
              } catch (FileException $e) {
                $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                return $this->redirectToRoute('app_contenu_photo');

                  
              }
             
              $photo->setFilename($newFilename);
              $photo->setPhotoPoster($user);
              $photo->setDateCreation(new \DateTime());
              $photo->setDescription($form->get('description')->getData()); 
              $photo->setAuteur($user->getNom());
             
              
              $em->persist($photo);
              $em->flush();

              $this->addFlash('success', 'Photo ajoutée.');
          return $this->redirectToRoute('app_compte_pro');
      
              
            }
          
          
      }
      return $this->render('contenu_photo/edit.html.twig', [
        'form' => $form->createView(),
    ]);
    
}

}

