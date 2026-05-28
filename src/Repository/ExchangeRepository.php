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
    //chercher les echanges acceptés
    public function findAcceptedExchanges(User $user): array
{
    return $this->createQueryBuilder('e')
        ->where('(e.userRequesting = :user OR e.userOffering = :user)')
        ->andWhere('e.status IN (:statuses)')
        ->setParameters([
            'user' => $user,
            'statuses' => 'accepted'
        ])
        ->orderBy('e.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
}
//chercher les echanges en attente
    public function findPendingRequests(User $user): array{
        return $this->createQueryBuilder('e')
                ->where('e.userOffering = :user')
                ->andWhere('e.status = :status')
                ->setParameters([
                    'user' => $user,
                    'status' => 'pending'
                    ])
                ->orderBy('e.createdAt','DESC')
                ->getQuery()
                ->getResult();
    }
    // rechercher les echanges complets
    public function findCompletedHistory(User $user){
        return $this->createQueryBuilder('e')
                    ->where('e.userRequesting = :user OR e.userOffering= :user')
                    ->andWhere('e.status = :status')
                    ->setParameters([
                        'user' => $user,
                        'status' => 'completed'
                    ])
                    ->orderBy('e.createdAt','DESC')
                    ->getQuery()
                    ->getResult();
    }
     public function findRefusedHistory(User $user){
        return $this->createQueryBuilder('e')
                    ->where('e.userRequesting = :user OR e.userOffering= :user')
                    ->andWhere('e.status = :status')
                    ->setParameters([
                        'user' => $user,
                        'status' => 'refused'
                    ])
                    ->orderBy('e.createdAt','DESC')
                    ->getQuery()
                    ->getResult();
    }
    public function findInProgressHistory(User $user){
        return $this->createQueryBuilder('e')
                    ->where('e.userRequesting = :user OR e.userOffering= :user')
        return $this->createQueryBuilder('e')
                    ->where('e.userRequesting = :user OR e.userOffering= :user')
                    ->andWhere('e.status = :status')
                    ->setParameters([
                        'user' => $user,
                        'status' => 'in_progress'
                    ])
                    ->orderBy('e.createdAt','DESC')
                    ->getQuery()
                    ->getResult();
    }
}