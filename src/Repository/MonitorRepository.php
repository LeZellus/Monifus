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

    private function createBaseMonitorQueryBuilder(): QueryBuilder
    {
        $truc = $this->createQueryBuilder('m')
            ->leftJoin('m.resource', 'r')
            ->leftJoin('m.prices', 'p')
            ->addSelect('m', 'r', 'p')
            ->addSelect('AVG(p.priceOne) as avgPriceOne, AVG(p.priceTen) as avgPriceTen, AVG(p.priceHundred) as avgPriceHundred')
            ->addSelect('COUNT(p) as priceCount')
            ->groupBy('m.id');

        return $truc;
    }

    public function findOneByIdWithResourceAndPrices($monitorId): ?Monitor
    {
        return $this->createQueryBuilder('m')
            ->leftJoin('m.resource', 'r')
            ->leftJoin('m.prices', 'p')
            ->addSelect('m', 'r', 'p')
            ->andWhere('m.id = :id')
            ->setParameter('id', $monitorId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getMonitorPriceAggregates($monitorId): ?array
    {
        // Calculer les agrégats pour le moniteur spécifié
        return $this->createQueryBuilder('m')
            ->select('AVG(p.priceOne) as avgPriceOne, AVG(p.priceTen) as avgPriceTen, AVG(p.priceHundred) as avgPriceHundred, COUNT(p) as priceCount')
            ->leftJoin('m.prices', 'p')
            ->andWhere('m.id = :id')
            ->setParameter('id', $monitorId)
            ->groupBy('m.id')
            ->getQuery()
            ->getSingleResult();
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
