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

    private function createDetailedPriceQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.User', 'u')
            ->leftJoin('p.Resource', 'r')
            ->addSelect('p', 'u', 'r');
    }

    // Récupération des moniteurs et leurs moyennes par utilisateur
    public function findByUserWithResourceAndPrices($userId): array|float|int|string
    {
        return $this->createBasePriceQueryBuilder()
            ->andWhere('u.id = :user')
            ->setParameter('user', $userId)
            ->getQuery()
            ->getArrayResult();
    }

    // Récupération du moniteur par utilisateur et par ressource, puis calcul de la moyenne
    public function findByUserAndResourceWithDetails($userId, $resourceId): array
    {
        $qb = $this->createDetailedPriceQueryBuilder()
            ->andWhere('u.id = :user')
            ->andWhere('r.id = :resource')
            ->setParameter('user', $userId)
            ->setParameter('resource', $resourceId);

        $priceDetails = $qb->getQuery()->getArrayResult();

        // Calcul des données agrégées
        $aggregatedData = [
            'avgPriceOne' => 0,
            'avgPriceTen' => 0,
            'avgPriceHundred' => 0,
            'priceCount' => count($priceDetails),
        ];

        if ($aggregatedData['priceCount'] > 0) {
            $aggregatedData['avgPriceOne'] = array_sum(array_column($priceDetails, 'priceOne')) / $aggregatedData['priceCount'];
            $aggregatedData['avgPriceTen'] = array_sum(array_column($priceDetails, 'priceTen')) / $aggregatedData['priceCount'];
            $aggregatedData['avgPriceHundred'] = array_sum(array_column($priceDetails, 'priceHundred')) / $aggregatedData['priceCount'];
        }

        return [
            'details' => $priceDetails,
            'aggregated' => $aggregatedData,
        ];
    }

    // Méthode de recherche sur les moniteurs
    public function findBySearchTerm($searchTerm): array|float|int|string
    {
        return $this->createBasePriceQueryBuilder()
            ->where('r.name LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$searchTerm.'%')
            ->getQuery()
            ->getArrayResult();
    }
}
