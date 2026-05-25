<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    public function findByTypeWithHost(string $type): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.host', 'h')
            ->addSelect('h')
            ->where('r.type = :type')
            ->setParameter('type', $type)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllWithHost(): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.host', 'h')
            ->addSelect('h')
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByHost(User $user): array
    {
        return $this->createQueryBuilder('r')
            ->where('r.host = :user')
            ->setParameter('user', $user)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}