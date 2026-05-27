<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function findFilteredQuery(
        string $search,
        string $genre,
        string $condition,
        string $sort
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('b');

        if ($search !== '') {
            $qb->andWhere('b.titre LIKE :search OR b.auteur LIKE :search')
               ->setParameter('search', '%' . $search . '%');
        }

        if ($genre !== '') {
            $qb->andWhere('b.genre = :genre')
               ->setParameter('genre', $genre);
        }

        if ($condition !== '') {
            $qb->andWhere('b.condition = :condition')
               ->setParameter('condition', $condition);
        }

        match ($sort) {
            'price_asc'  => $qb->orderBy('b.prix', 'ASC'),
            'price_desc' => $qb->orderBy('b.prix', 'DESC'),
            'title_az'   => $qb->orderBy('b.titre', 'ASC'),
            default      => $qb->orderBy('b.id', 'DESC'), // newest first
        };

        return $qb;
    }

    public function findBySearch(string $q): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.titre LIKE :q OR b.auteur LIKE :q OR b.genre LIKE :q')
            ->setParameter('q', '%' . $q . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}