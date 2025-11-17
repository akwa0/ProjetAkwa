<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * Récupère les messages reçus par un utilisateur
     */
    public function findMessagesRecusBy(Utilisateur $utilisateur, int $limit = 50): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.destinataire = :utilisateur')
            ->setParameter('utilisateur', $utilisateur)
            ->orderBy('m.dateEnvoie', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère la conversation entre deux utilisateurs
     */
    public function findConversation(Utilisateur $user1, Utilisateur $user2): array
    {
        return $this->createQueryBuilder('m')
            ->where(
                'm.emetteur = :user1 AND m.destinataire = :user2 OR m.emetteur = :user2 AND m.destinataire = :user1'
            )
            ->setParameter('user1', $user1)
            ->setParameter('user2', $user2)
            ->orderBy('m.dateEnvoie', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte les messages non lus d'un utilisateur
     */
    public function countNonLus(Utilisateur $utilisateur): int
    {
        return (int) $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->andWhere('m.destinataire = :utilisateur')
            ->andWhere('m.lu = false')
            ->setParameter('utilisateur', $utilisateur)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Récupère les utilisateurs avec qui on a une conversation active
     */
    public function findContactsList(Utilisateur $utilisateur): array
    {
        return $this->createQueryBuilder('m')
            ->select('DISTINCT CASE WHEN m.emetteur = :user THEN m.destinataire ELSE m.emetteur END as contact')
            ->where(
                'm.emetteur = :user OR m.destinataire = :user'
            )
            ->setParameter('user', $utilisateur)
            ->orderBy('m.dateEnvoie', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
