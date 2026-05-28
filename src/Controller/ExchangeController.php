use App\Repository\ExchangeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeController extends AbstractController
{

//initialiser la page  des échanges
    #[Route('/exchange/exchange', name: 'exchange_page')]
    public function init(ExchangeRepository $exchangeRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $acceptedExchanges = $exchangeRepository->findAcceptedExchanges($user);
        $pendingRequests = $exchangeRepository->findPendingRequests($user);
        $completedHistory = $exchangeRepository->findCompletedHistory($user);
        $refusedHistory = $exchangeRepository->findRefusedHistory($user);
        $inProgressHistory = $exchangeRepository->findInProgressHistory($user);
        
        $totalExchanges = count($acceptedExchanges) +  count($completedHistory) + count($inProgressHistory);
        $completedHistoryCount = count($completedHistory);
        $activeExchangesCount = count($acceptedExchanges) + count($inProgressHistory);

        $successRate = $totalExchanges > 0 ? ($completedHistoryCount / $totalExchanges) * 100 : 0;

        return $this->render('exchange/history.html.twig', [
            'user' => $user,
            'acceptedExchanges' => $acceptedExchanges,
            'pendingRequests' => $pendingRequests,
            'completedHistory' => $completedHistory,
            'refusedHistory' => $refusedHistory,
            'inProgressHistory' => $inProgressHistory,
            'successRate' => round($successRate, 2),
            'totalExchanges' => $totalExchanges,
            'completedHistoryCount' => $completedHistoryCount,
            'activeExchangesCount' => $activeExchangesCount
        ]);

    }

    #[Route('/exchange/{id}/accept', name: 'app_exchange_accept')]
public function accept(Exchange $exchange, EntityManagerInterface $em): Response
{
    if ($exchange->getUserOffering() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas le droit d'accepter cet échange.");
    }

    $exchange->setStatus('accepted');
    $em->flush();
    return $this->redirectToRoute('app_exchange');
}

#[Route('/exchange/{id}/decline', name: 'app_exchange_decline')]
public function decline(Exchange $exchange, EntityManagerInterface $em): Response
{
    if ($exchange->getUserOffering() !== $this->getUser()) {
        throw $this->createAccessDeniedException("Vous n'avez pas le droit de refuser cet échange.");
    }
    // notifier reqquesting user (fonctionnalité à implémenter)
    $exchange->setStatus('refused');
    $em->flush();
    return $this->redirectToRoute('app_exchange');
}

}