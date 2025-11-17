<?php

namespace App\Repository;

use App\Entity\Favoris;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Favoris>
 */
class FavorisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Favoris::class);
    }

    /**
     * Vérifie si un utilisateur a mis en favoris un artiste
     */
    public function findByUtilisateurAndArtiste(Utilisateur $utilisateur, Utilisateur $artiste): ?Favoris
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.utilisateur = :utilisateur')
            ->andWhere('f.artiste = :artiste')
            ->setParameter('utilisateur', $utilisateur)
            ->setParameter('artiste', $artiste)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Compte les favoris d'un artiste
     */
    public function countByArtiste(Utilisateur $artiste): int
    {
        return (int) $this->createQueryBuilder('f')
            ->select('COUNT(f.id)')
            ->andWhere('f.artiste = :artiste')
            ->setParameter('artiste', $artiste)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Récupère tous les artistes favoris d'un utilisateur
     */
    public function findByUtilisateur(Utilisateur $utilisateur): array
    {
        return $this->createQueryBuilder('f')
            ->join('f.artiste', 'a')
            ->andWhere('f.utilisateur = :utilisateur')
            ->setParameter('utilisateur', $utilisateur)
            ->orderBy('f.dateAjout', 'DESC')
            ->select('f, a')
            ->getQuery()
            ->getResult();
    }
}
