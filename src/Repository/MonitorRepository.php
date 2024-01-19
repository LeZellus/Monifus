<?php

namespace App\Repository;

use App\Entity\Monitor;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Monitor>
 *
 * @method Monitor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Monitor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Monitor[]    findAll()
 * @method Monitor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MonitorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Monitor::class);
    }

    public function save(Monitor $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Monitor $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findUniqueResourcesByUser(User $user)
    {
        $qb = $this->createQueryBuilder('m')
            ->select('r')
            ->join('m.resource', 'r')
            ->where('m.user = :user')
            ->setParameter('user', $user)
            ->groupBy('r.id');

        return $qb->getQuery()->getResult();
    }

    public function findMonitorAveragesByUser(User $user): array
    {
        $qb = $this->createQueryBuilder('m')
            ->select('r.id as resourceId', 'r.name as resourceName', 'r.imgUrl as resourceImg', 'AVG(m.pricePer1) as averagePriceUnit', 'AVG(m.pricePer10) as averagePriceTen', 'AVG(m.pricePer100) as averagePriceHundred')
            ->join('m.resource', 'r')
            ->where('m.user = :user')
            ->groupBy('r.id')
            ->setParameter('user', $user);

        return $qb->getQuery()->getArrayResult();
    }

    public function countByResource($resourceId) {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->where('m.resource = :resourceId')
            ->setParameter('resourceId', $resourceId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
