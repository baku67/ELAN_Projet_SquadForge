<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\User;
use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Topic>
 *
 * @method Topic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Topic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Topic[]    findAll()
 * @method Topic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    public function save(Topic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Topic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Topic[] Returns an array of Topic objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Topic
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }



    public function findLastTopics(int $maxResults = 5) 
    {
        return $this->createQueryBuilder('t')
            ->where('t.validated = :state')
            ->setParameter('state', "validated")
            ->orderBy('t.publish_date', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    public function findAllTopics(int $maxResults = 50) 
    {
        return $this->createQueryBuilder('t')
            ->where('t.validated = :state')
            ->setParameter('state', "validated")
            ->orderBy('t.publish_date', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    public function findGameLastTopics(Game $game, int $maxResults = 50) 
    {
        return $this->createQueryBuilder('t')
            ->where('t.game = :game')
            ->andwhere('t.validated = :state')
            ->setParameter('state', "validated")
            ->setParameter('game', $game)
            ->orderBy('t.publish_date', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }


    public function findUserLastTopics(User $user, int $maxResults = 5) 
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->orderBy('t.publish_date', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }

    public function countAllTopics()
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countUserTopics(User $user)
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countGameTopics(Game $game)
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id)')
            ->where('t.game = :game')
            ->setParameter('game', $game)
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function findByGameMin(Game $game, int $maxResults = 5) 
    {
        return $this->createQueryBuilder('t')
            ->where('t.game = :game')
            ->andwhere('t.validated = :state')
            ->setParameter('state', "validated")
            ->setParameter('game', $game)
            ->orderBy('t.publish_date', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();
    }


    // Modo: mini-liste des derniers topics en attente de vaidation
    public function findLastWaitingTopics(int $maxResults = 5)
    {

        return $this->createQueryBuilder('t')
            ->andwhere('t.validated = :state')
            ->setParameter('state', "waiting")
            ->orderBy('t.publish_date', 'DESC')
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();

    }

}
