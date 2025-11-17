<?php

namespace App\Repository;

use App\Entity\Like;
use App\Entity\Photo;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Like>
 */
class LikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Like::class);
    }

    /**
     * Vérifie si un utilisateur a liké une photo
     */
    public function findByPhotoAndUser(Photo $photo, Utilisateur $utilisateur): ?Like
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.photo = :photo')
            ->andWhere('l.utilisateur = :utilisateur')
            ->setParameter('photo', $photo)
            ->setParameter('utilisateur', $utilisateur)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Compte les likes d'une photo
     */
    public function countByPhoto(Photo $photo): int
    {
        return (int) $this->createQueryBuilder('l')
            ->select('COUNT(l.id)')
            ->andWhere('l.photo = :photo')
            ->setParameter('photo', $photo)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * Récupère toutes les photos likées par un utilisateur
     */
    public function findPhotosByUser(Utilisateur $utilisateur): array
    {
        return $this->createQueryBuilder('l')
            ->join('l.photo', 'p')
            ->join('p.photoPoster', 'u')
            ->andWhere('l.utilisateur = :utilisateur')
            ->setParameter('utilisateur', $utilisateur)
            ->orderBy('l.dateLike', 'DESC')
            ->select('l, p, u')
            ->getQuery()
            ->getResult();
    }
}
