<?php

namespace App\Repository;

use App\Entity\Monster;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Monster>
 *
 * @method Monster|null find($id, $lockMode = null, $lockVersion = null)
 * @method Monster|null findOneBy(array $criteria, array $orderBy = null)
 * @method Monster[]    findAll()
 * @method Monster[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonsterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Monster::class);
    }

    public function findMonstersWithRecords()
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.records', 'r')
            ->where('r.isApproved = true')
            ->groupBy('m.id')
            ->getQuery()
            ->getResult();
    }


    public function countRecordsForEachMonster()
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.records', 'r')
            ->select('m.id, m.name, COUNT(r.id) as recordCount')
            ->where('r.isApproved = true')
            ->groupBy('m.id')
            ->getQuery()
            ->getResult();
    }

}
