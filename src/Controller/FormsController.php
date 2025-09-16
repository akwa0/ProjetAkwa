<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

use App\Form\MessageType;
use App\Entity\Message;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;

final class FormsController extends AbstractController
{
    #[Route('/afficher/form', name: 'app_forms')]
    public function afficherForm(): Response
    {
        $formMessage = $this->createForm(MessageType::class);

        $vars = ['formMessage' => $formMessage];

        return $this->render('forms/afficher_form.html.twig', $vars);
    }
    #[Route('/form/envoyer/message', name: 'app_forms_envoyer_message')]
    public function envoyerMessage(Request $request, EntityManagerInterface $em): Response
    {
        $message = new Message();
        $formMessage = $this->createForm(MessageType::class, $message);

        $formMessage->handleRequest($request);


        
        if ($formMessage->isSubmitted()) {
            // mise à jour de la date
            $message->setDateEnvoie(new DateTime());
            $em->persist($message);
            $em->flush();
            return $this->redirectToRoute('app_form_afficher_message');
        } 
        else {

            $vars = ['formMessage' => $formMessage];
            return $this->render('forms/affiche_form_envoyer_message.html.twig', $vars);
        }
    }
    #[Route('/forms/afficher/messages', name: 'app_form_afficher_message')]
    public function afficherMessage(EntityManagerInterface $em)
    {
        $rep = $em->getRepository(Message::class);
        $arrayMessage = $rep->findAll();

        $vars = ['message' => $arrayMessage];
        return $this->render('forms/afficher_message.html.twig', $vars);
    }
}
