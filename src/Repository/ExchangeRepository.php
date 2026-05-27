<?php

namespace App\Repository;

use App\Entity\Exchange;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exchange>
 */
class ExchangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exchange::class);
    }
    public function findActiveExchanges(User $user): array
{
    return $this->createQueryBuilder('e')
        ->where('(e.userRequesting = :user OR e.userOffering = :user)')
        ->andWhere('e.status IN (:statuses)')
        ->setParameters([
            'user' => $user,
            'statuses' => ['accepted', 'in_progress']
        ])
        ->orderBy('e.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
}
}
