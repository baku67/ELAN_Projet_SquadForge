<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Game;
use App\Entity\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Group>
 *
 * @method Group|null find($id, $lockMode = null, $lockVersion = null)
 * @method Group|null findOneBy(array $criteria, array $orderBy = null)
 * @method Group[]    findAll()
 * @method Group[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function save(Group $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Group $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByWithoutMembership($user): array
    {
        return $this->createQueryBuilder('g')
            ->where(':user NOT MEMBER OF g.members')
            ->andwhere('g.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', "public")
            ->getQuery()
            ->getResult();
    }



    public function findBySearchLandingPage(string $query, int $gameSelectedId)
    {
        $queryGroups = $this->createQueryBuilder('g')
        ->select('g', 'game.title, game.color, game.tinyLogo')
        ->leftJoin('g.game', 'game')
        ->where('g.title LIKE :searchText')
        ->setParameter('searchText', "%$query%");

        // Si 0: aucun jeu séléctionné
        if($gameSelectedId != 0) {
            $queryGroups->andWhere('g.game = :gameSelectedId')
            ->setParameter('gameSelectedId', $gameSelectedId);
        }

        $queryGroups->setMaxResults(5);
        $resultTeams = $queryGroups->getQuery()->getResult(); 
        return $resultTeams;
    }


    
    // TODO: c'est des findBy ?
    public function findAllByGame(Game $game): array
    {
        return $this->createQueryBuilder('g')
        // ->innerJoin('g.members', 'u')
        ->where('g.game = :game')
        ->andwhere('g.status = :status')
        ->setParameter('status', "public")
        ->setParameter('game', $game)
        ->orderBy('g.creation_date', 'DESC')
        // ->setMaxResults(50)
        ->getQuery()
        ->getResult();
    }


    // Liste des groups dont l'user est membre/(leader? sépraré)
    public function findUserGroups(User $user): array
    {   
        // where status = public, game, orderBy
        return $this->createQueryBuilder('g')
        ->where('g.leader = :leader')
        ->setParameter('leader', $user->getId())
        ->orderBy('g.creation_date', 'DESC')
        // ->setMaxResults(50)
        ->getQuery()
        ->getResult();
    }


    // Liste des groups du User sur jeuDetail
    public function findGroupsByUserAndGame(User $user, Game $game): array
    {

        return $this->createQueryBuilder('g')
            ->innerJoin('g.members', 'u')
            ->where('u = :user')
            ->andWhere('g.game = :game')
            ->setParameter('user', $user)
            ->setParameter('game', $game)
            ->getQuery()
            ->getResult();
    }



//    /**
//     * @return Group[] Returns an array of Group objects
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

//    public function findOneBySomeField($value): ?Group
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
