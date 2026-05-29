<?php

namespace App\Repository;

use App\Entity\User;
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

    public function findAcceptedExchanges(User $user): array
    {
        return $this->createQueryBuilder('e')
            ->where('(e.userRequesting = :user OR e.userOffering = :user)')
            ->andWhere('e.status = :status')
            ->setParameter('user', $user)          
            ->setParameter('status', 'accepted')  
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findPendingRequests(User $user): array 
    {
        return $this->createQueryBuilder('e')
            ->where('e.userOffering = :user')
            ->andWhere('e.status = :status')
            ->setParameter('user', $user)          
            ->setParameter('status', 'pending')    
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findCompletedHistory(User $user): array
    {
        return $this->createQueryBuilder('e')
            ->where('(e.userRequesting = :user OR e.userOffering = :user)') // Ajout des parenthèses de sécurité pour le OR
            ->andWhere('e.status = :status')
            ->setParameter('user', $user)          
            ->setParameter('status', 'completed')  
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findRefusedHistory(User $user): array
    {
        return $this->createQueryBuilder('e')
            ->where('(e.userRequesting = :user OR e.userOffering = :user)') // Ajout des parenthèses de sécurité pour le OR
            ->andWhere('e.status = :status')
            ->setParameter('user', $user)          
            ->setParameter('status', 'refused')    
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findInProgressHistory(User $user): array
    {
        return $this->createQueryBuilder('e')
            ->where('(e.userRequesting = :user OR e.userOffering = :user)') // Ajout des parenthèses de sécurité pour le OR
            ->andWhere('e.status = :status')
            ->setParameter('user', $user)          
            ->setParameter('status', 'in_progress')
            ->orderBy('e.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}