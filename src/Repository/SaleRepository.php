<?php

namespace App\Repository;

use App\Entity\Sale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function getSaleStatsForUser($user)
    {
        $result = $this->createQueryBuilder('s')
            ->select(
                'SUM(s.buyPrice) as totalBuyPrice',
                'SUM(CASE WHEN s.isSell = true THEN s.sellPrice ELSE 0 END) as totalSellPrice',
                'SUM(CASE WHEN s.isSell = false THEN s.sellPrice ELSE 0 END) as totalPendingPrice',
                'COUNT(s) as totalSaleCount',
                'SUM(CASE WHEN s.isSell = true THEN 1 ELSE 0 END) as totalSaled'
            )
            ->where('s.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleResult();

        // Calculs supplémentaires
        $result['profit'] = $result['totalSellPrice'] - $result['totalBuyPrice'];
        $result['percentProfit'] = ($result['totalBuyPrice'] > 0) ? ($result['profit'] / $result['totalBuyPrice']) * 100 : 0;
        $result['taxe'] = $result['totalSellPrice'] * 0.01;

        return $result;
    }
}
