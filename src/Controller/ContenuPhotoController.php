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

final class ContenuPhotoController extends AbstractController
{
    #[Route('/contenu/photo', name: 'app_contenu_photo')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
      /** @var Utilisateur|null $user */
      $user = $this->getUser();
      if (!$user instanceof Utilisateur) {
          // Not logged in or unexpected user type
          return $this->redirectToRoute('app_login');
      }
      $form = $this->createForm(UserSettingsType::class, $user,['compte_pro'=>'true']);
      $form->handleRequest($request);
      
      if ($form->isSubmitted() && $form->isValid()) {
          $imageFile = $form->get('newphoto')->getData();

          if ($imageFile) {
              $newFilename = uniqid().'.'.$imageFile->guessExtension();
      
              try {
                  $imageFile->move(
                      $this->getParameter('uploads_directory'),
                      $newFilename
                  );
              } catch (FileException) {
                  
              }
              $photo = new Photo();
              $photo->setFilename($newFilename);
              $photo->setPhotoPoster($user);
              $user->addPhoto($photo);
              $em->persist($photo);
      
              // Met à jour le nom de fichier dans l'entité
              $user->setNewphoto($newFilename);
          }
          $em->flush();
          $this->addFlash('success', 'Photo ajoutée.');
          return $this->redirectToRoute('app_compte_pro');
      }
      return $this->render('contenu_photo/edit.html.twig', [
        'form' => $form->createView(),
    ]);
    
}
