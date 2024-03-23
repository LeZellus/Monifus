<?php

namespace App\Repository;

use App\Entity\Sale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sale>
 *
 * @method Sale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sale[]    findAll()
 * @method Sale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    public function findSalesByUserAndResourceName($user, $searchTerm): Query
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.resource', 'r') // Assurez-vous que 'resource' est la relation correcte
            ->where('s.user = :user')
            ->setParameter('user', $user);

        if (!empty($searchTerm)) {
            $qb->andWhere('r.name LIKE :term') // 'name' doit être le champ dans Resource à rechercher
            ->setParameter('term', '%'.$searchTerm.'%');
        }

        return $qb->getQuery();
    }

    public function getSaleStatsForUserAndResourceSearch($user, $searchTerm)
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.resource', 'r')
            ->select(
                'SUM(s.buyPrice) as totalBuyPrice',
                'SUM(CASE WHEN s.isSell = true THEN s.sellPrice ELSE 0 END) as totalSellPrice',
                'SUM(CASE WHEN s.isSell = false THEN s.sellPrice ELSE 0 END) as totalPendingPrice',
                'COUNT(s) as totalSaleCount',
                'SUM(CASE WHEN s.isSell = true THEN 1 ELSE 0 END) as totalSaled'
            )
            ->where('s.user = :user')
            ->setParameter('user', $user);

        if (!empty($searchTerm)) {
            $qb->andWhere('r.name LIKE :term')
                ->setParameter('term', '%'.$searchTerm.'%');
        }

        $result = $qb->getQuery()->getSingleResult();

        // Calculs supplémentaires
        $result['profit'] = $result['totalSellPrice'] - $result['totalBuyPrice'];
        $result['percentProfit'] = ($result['totalBuyPrice'] > 0) ? ($result['profit'] / $result['totalBuyPrice']) * 100 : 0;
        $result['taxe'] = $result['totalSellPrice'] * 0.01;

        return $result;
    }

    public function findTopSalesByBuySellRatio(): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.isSell = :isSell')
            ->setParameter('isSell', true)
            ->andWhere('s.buyPrice IS NOT NULL')
            ->andWhere('s.sellPrice IS NOT NULL')
            ->orderBy('s.sellPrice / s.buyPrice', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
