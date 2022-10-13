<?php

namespace App\Repository;

use App\Entity\Monitor;
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

    //Obtenir la moyenne de tout les monitors
    public function getAverageMonitors(MonitorRepository $monitorRepository) {
        $qb = $monitorRepository->createQueryBuilder('m');
        $qb
            ->select($qb->expr()->avg('m.price'))
            ->where('')
            ->orderBy('m.resource', 'ASC')
            ->getQuery()
            ->getResult();


        return $qb->getQuery()->getResult();
    }

    //Je veux récupérer chaque ressource des moniteurs
    public function getMonitorByResourceByUser(MonitorRepository $monitorRepository, $identifier) {
        $qb = $monitorRepository->createQueryBuilder('m');
        $qb
            ->leftJoin('m.resource', 'r')
            ->distinct()
            ->leftJoin('m.user', 'u')
            ->where('u.email = :identifier')
            ->setParameter('identifier', $identifier)
            ->getQuery()
            ->getResult();


        return $qb->getQuery()->getResult();
    }

    //Obtenir la moyenne de tout les monitors par lot de 100 par utilisateur
    public function getAverageMonitorsByStockByUserByQuantity(MonitorRepository $monitorRepository,Int $quantity = 1) {
        $qb = $monitorRepository->createQueryBuilder('m');

        $qb
            ->select($qb->expr()->avg('m.price'))
            ->leftJoin('m.stock', 's')
            ->leftJoin('m.resource', 'r')
            ->andwhere('r.id = :idResource')
            ->andWhere('s.quantity = :quantity')
            ->setParameter('idResource', 5)
            ->setParameter('quantity', $quantity)
            ->getQuery()
            ->getResult();

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Monitor[] Returns an array of Monitor objects
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

//    public function findOneBySomeField($value): ?Monitor
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
