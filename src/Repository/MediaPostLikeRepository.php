<?php

namespace App\Repository;

use App\Entity\MediaPostLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MediaPostLike>
 *
 * @method MediaPostLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediaPostLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediaPostLike[]    findAll()
 * @method MediaPostLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaPostLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediaPostLike::class);
    }

    public function save(MediaPostLike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MediaPostLike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return MediaPostLike[] Returns an array of MediaPostLike objects
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

//    public function findOneBySomeField($value): ?MediaPostLike
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
