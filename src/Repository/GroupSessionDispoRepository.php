<?php

namespace App\Repository;

use App\Entity\GroupSessionDispo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GroupSessionDispo>
 *
 * @method GroupSessionDispo|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupSessionDispo|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupSessionDispo[]    findAll()
 * @method GroupSessionDispo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupSessionDispoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupSessionDispo::class);
    }


    public function remove(GroupSessionDispo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
//    /**
//     * @return GroupSessionDispo[] Returns an array of GroupSessionDispo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GroupSessionDispo
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
