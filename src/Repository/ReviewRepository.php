<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    public function findLatest(int $limit = 6): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function getAverageRating(): float
    {
        $result = $this->createQueryBuilder('r')
            ->select('AVG(r.rating) as avg')
            ->getQuery()
            ->getSingleScalarResult();

        return round((float) $result, 1);
    }
}