<?php

namespace App\Repository;

use App\Entity\Guild;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Guild>
 *
 * @method Guild|null find($id, $lockMode = null, $lockVersion = null)
 * @method Guild|null findOneBy(array $criteria, array $orderBy = null)
 * @method Guild[]    findAll()
 * @method Guild[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GuildRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guild::class);
    }

    public function isUserLeaderOfGuild($userId)
    {
        $qb = $this->createQueryBuilder('g')
            ->where('g.leader = :user')
            ->setParameter('user', $userId)
            ->getQuery();

        return (bool) $qb->getOneOrNullResult();
    }
}
