<?php

use App\Repository\ExchangeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeController extends AbstractController
{

//initialiser la page d'historique des échanges
    #[Route('/exchange/index', name: 'exchange_page')]
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
}