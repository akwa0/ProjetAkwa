<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Utilisateur;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/messagerie', name: 'app_messagerie')]
#[IsGranted('ROLE_USER')]
class MessagerieController extends AbstractController
{
    /**
     * Affiche la liste des conversations ou redirige vers une conversation spécifique
     */
    #[Route('', name: '', methods: ['GET'])]
    public function index(
        Request $request,
        MessageRepository $messageRepository,
        UtilisateurRepository $utilisateurRepository
    ): Response {
        $user = $this->getUser();

        // Si un contact est spécifié, rediriger vers la conversation
        $contact = $request->query->get('contact');
        if ($contact) {
            $contactUser = $utilisateurRepository->find((int) $contact);
            if ($contactUser && $contactUser->getId() !== $user->getId()) {
                return $this->redirectToRoute('app_messagerie_conversation', ['id' => $contactUser->getId()]);
            }
        }

        // Récupérer les messages reçus pour afficher les conversations
        $messages = $messageRepository->findMessagesRecusBy($user, 100);

        // Compter les non-lus
        $nonLus = $messageRepository->countNonLus($user);

        // Déterminer la page de retour selon le rôle
        $isProAccount = in_array('ROLE_ADMIN', $user->getRoles(), true);
        $backRoute = $isProAccount ? 'app_compte_pro' : 'app_compte_client';

        return $this->render('messagerie/index.html.twig', [
            'messages' => $messages,
            'nonLus' => $nonLus,
            'backRoute' => $backRoute,
        ]);
    }

    /**
     * Affiche une conversation avec un utilisateur
     */
    #[Route('/conversation/{id}', name: '_conversation', methods: ['GET', 'POST'])]
    public function conversation(
        Utilisateur $contact,
        Request $request,
        MessageRepository $messageRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        // Vérifier qu'on ne regarde pas sa propre conversation
        if ($user->getId() === $contact->getId()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas converser avec vous-même');
        }

        // Récupérer la conversation
        $messages = $messageRepository->findConversation($user, $contact);

        // Marquer les messages du contact comme lus
        foreach ($messages as $message) {
            if ($message->getDestinataire() === $user && !$message->isLu()) {
                $message->setLu(true);
            }
        }
        $entityManager->flush();

        // Créer le formulaire pour envoyer un message
        $message = new Message();
        $message->setEmetteur($user);
        $message->setDestinataire($contact);

        $form = $this->createForm(MessageType::class, $message, [
            'contact' => $contact,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setDateEnvoie(new \DateTime());
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_messagerie_conversation', ['id' => $contact->getId()]);
        }

        return $this->render('messagerie/conversation.html.twig', [
            'contact' => $contact,
            'messages' => $messages,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Envoyer un message rapide (via AJAX optionnel)
     */
    #[Route('/envoyer/{id}', name: '_envoyer', methods: ['POST'])]
    public function envoyer(
        Utilisateur $contact,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        if ($user->getId() === $contact->getId()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas converser avec vous-même');
        }

        $message = new Message();
        $message->setEmetteur($user);
        $message->setDestinataire($contact);

        $form = $this->createForm(MessageType::class, $message, [
            'contact' => $contact,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setDateEnvoie(new \DateTime());
            $entityManager->persist($message);
            $entityManager->flush();

            $this->addFlash('success', 'Message envoyé avec succès');
        }

        return $this->redirectToRoute('app_messagerie_conversation', ['id' => $contact->getId()]);
    }

    /**
     * Envoyer un message à un utilisateur (depuis son profil)
     */
    #[Route('/nouveau/{id}', name: '_nouveau', methods: ['GET', 'POST'])]
    public function nouveau(
        Utilisateur $contact,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $this->getUser();

        if ($user->getId() === $contact->getId()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas converser avec vous-même');
        }

        // Rediriger vers la conversation existante
        return $this->redirectToRoute('app_messagerie_conversation', ['id' => $contact->getId()]);
    }
}
