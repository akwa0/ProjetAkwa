<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

//importer les entities
use App\Entity\Photo;

final class AcceuilController extends AbstractController
{
    #[Route('acceuil/index.html.twig', name: 'app_accueil')]
    public function testModele (EntityManagerInterface $em){
        //on va obtenir ds entités de la BD 
        //1.Obtenir le repode l'entité
        $rep = $em->getRepository(Photo::class);
        $photos = $rep->findAll();
        // dd($photos);
        $var =[
            'photos'=>$photos,
        ];
        return $this->render('acceuil/test_modele.html.twig', $var);
    }

}