<?php

namespace App\Repository;

use App\Entity\Record;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Record>
 *
 * @method Record|null find($id, $lockMode = null, $lockVersion = null)
 * @method Record|null findOneBy(array $criteria, array $orderBy = null)
 * @method Record[]    findAll()
 * @method Record[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Record::class);
    }

    public function findRecordsWithUserByMonster($monsterId)
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.user', 'u')
            ->addSelect('u') // Sélectionnez l'utilisateur pour éviter une requête supplémentaire
            ->where('r.monster = :monsterId')
            ->andWhere('r.isApproved = true')
            ->setParameter('monsterId', $monsterId)
            ->orderBy('r.time', 'ASC') // Tri par le temps du record, du plus bas au plus haut
            ->getQuery()
            ->getResult();
    }

    public function findBestTimeForAllMonsters()
    {
        // D'abord, obtenir le temps le plus bas pour chaque monstre pour les records valides
        $subQuery = $this->createQueryBuilder('r2')
            ->select('MIN(r2.time) as minTime')
            ->where('r2.monster = m.id')
            ->andWhere('r2.isApproved = true')  // Assurez-vous que les records sont valides
            ->groupBy('r2.monster');

        // Ensuite, obtenir le record correspondant à ce temps pour chaque monstre
        return $this->createQueryBuilder('r')
            ->innerJoin('r.monster', 'm')
            ->addSelect('m')
            ->where('r.time = (' . $subQuery->getDQL() . ')')
            ->andWhere('r.isApproved = true')  // Assurez-vous à nouveau que les records sont valides
            ->getQuery()
            ->getResult();
    }
}
