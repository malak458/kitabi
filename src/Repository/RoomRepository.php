<?php

namespace App\Repository;

use App\Entity\Room;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Room>
 */
class RoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Room::class);
    }

    /**
     * Récupère les rooms par type en chargeant directement le host associé
     * @return Room[]
     */
    public function findByTypeWithHost(string $type): array
    {
        return $this->createQueryBuilder('r')
            ->leftJoin('r.host', 'h') // On fait la jointure avec l'entité User (host)
            ->addSelect('h')          // On force la sélection des données du host
            ->where('r.type = :type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }
}