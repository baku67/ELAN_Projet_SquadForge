<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Topic;
use App\Entity\TopicPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TopicPost>
 *
 * @method TopicPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method TopicPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method TopicPost[]    findAll()
 * @method TopicPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicPostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TopicPost::class);
    }

    public function save(TopicPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TopicPost $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function verifyDelayPublish(User $user, Topic $topic, $oneHourAgo) 
    {
        $query = $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->andWhere('t.topic = :topic')
            ->andWhere('t.publish_date >= :oneHourAgo')
            ->setParameter('user', $user)
            ->setParameter('topic', $topic)
            ->setParameter('oneHourAgo', $oneHourAgo);

        $topicPostFound = $query->getQuery()->getResult();  

        return $topicPostFound;
    }


//    /**
//     * @return TopicPost[] Returns an array of TopicPost objects
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

//    public function findOneBySomeField($value): ?TopicPost
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
