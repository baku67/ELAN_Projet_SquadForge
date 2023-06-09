<?php

namespace App\Repository;

use App\Entity\TopicPost;
use App\Entity\PostLike;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PostLike>
 *
 * @method PostLike|null find($id, $lockMode = null, $lockVersion = null)
 * @method PostLike|null findOneBy(array $criteria, array $orderBy = null)
 * @method PostLike[]    findAll()
 * @method PostLike[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostLikeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PostLike::class);
    }

    public function save(PostLike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PostLike $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return PostLike[] Returns an array of PostLike objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PostLike
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function countTopicPostUpvotes(TopicPost $topicPost) 
    {
        return $this->createQueryBuilder('pl')
            ->select('COUNT(pl.id)')
            ->where('pl.topicPost = :topicPost')
            ->andWhere('pl.state = :upvote')
            ->setParameter('topicPost', $topicPost)
            ->setParameter('upvote', "upvote")
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countTopicPostDownvotes(TopicPost $topicPost) 
    {
        return $this->createQueryBuilder('pl')
            ->select('COUNT(pl.id)')
            ->where('pl.topicPost = :topicPost')
            ->andWhere('pl.state = :downvote')
            ->setParameter('topicPost', $topicPost)
            ->setParameter('downvote', "downvote")
            ->getQuery()
            ->getSingleScalarResult();
    }


    public function calcTopicPostScore(TopicPost $topicPost) 
    {
        $topicPostUpvotesNbr = $this->countTopicPostUpvotes($topicPost);
        $topicPostDownvotesNbr = $this->countTopicPostDownvotes($topicPost);

        return $topicPostUpvotesNbr - $topicPostDownvotesNbr;
    }
}
