<?php

namespace App\Controller;

use App\Entity\Exchange;
use App\Repository\ExchangeRepository;
use Doctrine\ORM\EntityManagerInterface; // <-- AJOUTÉ : Import manquant
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeController extends AbstractController
{
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
        
        $totalExchanges = count($acceptedExchanges) + count($completedHistory) + count($inProgressHistory);
        $completedHistoryCount = count($completedHistory);
        $activeExchangesCount = count($acceptedExchanges) + count($inProgressHistory);

        $successRate = $totalExchanges > 0 ? ($completedHistoryCount / $totalExchanges) * 100 : 0;

        return $this->render('exchange/exchange.html.twig', [
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
        
        return $this->redirectToRoute('exchange_page'); 
    }

    #[Route('/exchange/{id}/decline', name: 'app_exchange_decline')]
    public function decline(Exchange $exchange, EntityManagerInterface $em): Response
    {
        if ($exchange->getUserOffering() !== $this->getUser()) {
            throw $this->createAccessDeniedException("Vous n'avez pas le droit de refuser cet échange.");
        }
        
        $exchange->setStatus('refused');
        $em->flush();
        
        return $this->redirectToRoute('exchange_page'); 
    }
    #[Route('/exchange/update-status', name: 'app_exchange_update_status', methods: ['POST'])]
public function updateStatus(
    \Symfony\Component\HttpFoundation\Request $request, 
    \App\Repository\ExchangeRepository $exchangeRepository, // Remplacez par le nom de votre Repository si différent
    \Doctrine\ORM\EntityManagerInterface $em
): \Symfony\Component\HttpFoundation\JsonResponse {
    
    $data = json_decode($request->getContent(), true);
    $exchangeId = $data['exchangeId'] ?? null;
    $status = $data['status'] ?? null;

    if (!$exchangeId || !$status) {
        return new \Symfony\Component\HttpFoundation\JsonResponse(['success' => false, 'error' => 'Données manquantes.']);
    }

    $exchange = $exchangeRepository->find($exchangeId);

    if (!$exchange) {
        return new \Symfony\Component\HttpFoundation\JsonResponse(['success' => false, 'error' => 'Échange introuvable.']);
    }

    // Change le statut de l'échange (Vérifiez si votre entité utilise setStatus ou une autre méthode)
    $exchange->setStatus($status);
    $em->flush();

    return new \Symfony\Component\HttpFoundation\JsonResponse(['success' => true]);
}

}