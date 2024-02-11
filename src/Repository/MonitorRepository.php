<?php

namespace App\Repository;

use App\Entity\Monitor;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    //Récupère la ressource, son nom, son image, et la moyennne par lot.
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

    private function createBaseMonitorQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.resource', 'r')
            ->leftJoin('m.prices', 'p')
            ->addSelect('m', 'r', 'p')
            ->addSelect('AVG(p.priceOne) as avgPriceOne, AVG(p.priceTen) as avgPriceTen, AVG(p.priceHundred) as avgPriceHundred')
            ->addSelect('COUNT(p) as priceCount')
            ->groupBy('m.id');
    }

    public function findByUserWithResourceAndPrices($userId): array|float|int|string
    {
        return $this->createBaseMonitorQueryBuilder()
            ->andWhere('m.user = :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getArrayResult();
    }

    public function findBySearchTerm($searchTerm): array|float|int|string
    {
        return $this->createBaseMonitorQueryBuilder()
            ->where('r.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getArrayResult();
    }
}
