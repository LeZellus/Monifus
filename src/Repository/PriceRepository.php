<?php

namespace App\Repository;

use App\Entity\Price;
use App\Entity\Resource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Price>
 *
 * @method Price|null find($id, $lockMode = null, $lockVersion = null)
 * @method Price|null findOneBy(array $criteria, array $orderBy = null)
 * @method Price[]    findAll()
 * @method Price[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Price::class);
    }

    private function createBasePriceQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.User', 'u') // Remplacez 'u' par l'alias approprié pour l'entité User
            ->leftJoin('p.Resource', 'r') // Remplacez 'r' par l'alias approprié pour l'entité Resource
            ->addSelect('p', 'u', 'r')
            ->addSelect('AVG(p.priceOne) as avgPriceOne, AVG(p.priceTen) as avgPriceTen, AVG(p.priceHundred) as avgPriceHundred')
            ->addSelect('COUNT(p) as priceCount')
            ->groupBy('r.id'); // Supposant que vous voulez grouper par l'identifiant de la ressource
    }

    public function findByUserWithResourceAndPrices($userId): array|float|int|string
    {
        return $this->createBasePriceQueryBuilder()
            ->andWhere('u.id = :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getArrayResult();
    }

    public function findPriceAggregatesByUserAndResource($userId, $resourceId): array
    {
        $queryBuilder = $this->createBasePriceQueryBuilder()
            ->andWhere('u.id = :user')
            ->andWhere('r.id = :resource')
            ->setParameter('user', $userId)
            ->setParameter('resource', $resourceId);

        // Vous pouvez ajouter d'autres statistiques si nécessaire
        $queryBuilder->select('AVG(p.priceOne) as avgPriceOne',
            'AVG(p.priceTen) as avgPriceTen',
            'AVG(p.priceHundred) as avgPriceHundred',
            'COUNT(p) as priceCount');

        return $queryBuilder->getQuery()->getArrayResult();
    }

    public function findBySearchTerm($searchTerm): array|float|int|string
    {
        return $this->createBasePriceQueryBuilder()
            ->where('r.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getArrayResult();
    }
}
