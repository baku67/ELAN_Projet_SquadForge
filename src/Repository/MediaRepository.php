<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\Media;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Media>
 *
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }

    public function save(Media $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Media $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Media[] Returns an array of Media objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Media
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    // Pair mieux pour grid pair
    public function findLastMedias(int $maxResults = 8) 
    {
        return $this->createQueryBuilder('m')
            ->where('m.validated = :state')
            ->setParameter('state', "validated")
            ->orderBy('m.publish_date', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    public function findGlobalLastMedias(int $maxResults = 50) 
    {
        return $this->createQueryBuilder('m')
            ->where('m.validated = :state')
            ->setParameter('state', "validated")
            ->orderBy('m.publish_date', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    public function findUserLastMedias(User $user, int $maxResults = 8) 
    {
        return $this->createQueryBuilder('m')
            ->where('m.user = :user')
            ->setParameter('user', $user)
            ->orderBy('m.publish_date', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    public function countUserMedias(User $user)
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->where('m.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countGlobalMedias()
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function findGameLastMedias(Game $game, int $maxResults = 20)
    {
        return $this->createQueryBuilder('m')
        ->where('m.game = :game')
        ->andwhere('m.validated = :state')
        ->setParameter('state', "validated")
        ->setParameter('game', $game)
        ->orderBy('m.publish_date', 'DESC')
        ->setMaxResults($maxResults)
        ->getQuery()
        ->getResult();
    }

    public function countGameMedias(Game $game)
    {
        return $this->createQueryBuilder('m')
            ->select('COUNT(m.id)')
            ->where('m.game = :game')
            ->setParameter('game', $game)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function findByGameMin(Game $game, int $maxResults = 10) 
    {
        return $this->createQueryBuilder('m')
            ->where('m.game = :game')
            ->andwhere('m.validated = :state')
            ->setParameter('state', "validated")
            ->setParameter('game', $game)
            ->orderBy('m.publish_date', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }
}
