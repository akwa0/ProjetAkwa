<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\AvisType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Avis;
use DateTime;
use Symfony\Component\HttpFoundation\Request;

final class FormAvisController extends AbstractController
{
    #[Route('/form/avis', name: 'app_form_avis')]
    public function afficherFormAvis(): Response
    {
        $formAvis = $this->createForm(AvisType::class);  

        $vars = ['formAvis' => $formAvis];
        return $this->render('form_avis/formAvis.html.twig', $vars);
    }

    #[Route('/formAvis/envoyer/avis', name:'app_form_envoyer_avis')]
    public function envoyerAvis(Request $request, EntityManagerInterface $em): Response
    {
        $avis= new Avis();
        $formAvis = $this->createForm(AvisType::class, $avis);

        $formAvis->handleRequest($request);

        if ($formAvis->isSubmitted()) {
            $avis->setDateCreation(new DateTime());

            $em->persist($avis);
            $em->flush();
            return $this->redirectToRoute('app_form_afficher_avis');
        }
        else {
            $vars = ['formAvis' => $formAvis];
            return $this->render('form_avis/affiche_formAvis.html.twig', $vars);
        }
    }

    #[Route('/formAvis/afficheravis', name: 'app_form_afficher_avis')]
    public function afficherAvis(EntityManagerInterface $em)
    {
        $rep = $em->getRepository(Avis::class);
        $arrayAvis = $rep->findAll();

        $vars = ['avis' => $arrayAvis];
        return $this->render('form_avis/afficher_avis.html.twig', $vars);
    }
}
