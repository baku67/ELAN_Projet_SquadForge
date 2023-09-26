<?php

namespace App\Repository;

use App\Entity\Report;
use App\Entity\User;
use App\Repository\ReportMotifRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Report>
 *
 * @method Report|null find($id, $lockMode = null, $lockVersion = null)
 * @method Report|null findOneBy(array $criteria, array $orderBy = null)
 * @method Report[]    findAll()
 * @method Report[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private ReportMotifRepository $reportMotifRepo)
    {
        parent::__construct($registry, Report::class);
    }

    public function save(Report $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Report $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    // Tout les Reports regroupées par objet
    public function getAllReportsGroupedByOjectIdAndType(): array {
        return $this->createQueryBuilder('r')
        ->select('r.objectId, r.objectType, COUNT(r) as nbrReports') 
        ->orderBy('nbrReports', 'DESC')
        ->groupBy('r.objectId', 'r.objectType') 
        ->getQuery()
        ->getResult();
    }


    // Pour chaque motif de report existant, compter le nombre de reports pour ce motif et selon objet passé en paramètres
    public function getNbrReportsPerMotif(string $objectType, int $objectId): array {
        
        $motifs = $this->reportMotifRepo->findAll();
        $nbrReportsPerMotif = [];

        foreach ($motifs as $motif) {

            $nbr = $this->createQueryBuilder('r')
            ->select('COUNT(r) as nbrReports')
            ->where('r.reportMotif = :motif')
            ->setParameter('motif', $motif)

            ->andWhere('r.objectType = :objectType')
            ->setParameter('objectType', $objectType)
            ->andWhere('r.objectId = :objectId')
            ->setParameter('objectId', $objectId)

            ->getQuery()
            ->getSingleScalarResult();

            if ($nbr > 0) {
                $nbrReportsPerMotif[$motif->getText()] = $nbr;
            }
        }
        return $nbrReportsPerMotif;
    }


//    /**
//     * @return Report[] Returns an array of Report objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Report
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
