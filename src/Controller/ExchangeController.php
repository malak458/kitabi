<?php
namespace App\Controller;
use App\Entity\Exchange;
use App\Repository\ExchangeRepository;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExchangeController extends AbstractController{

    #[Route('/exchange/exchange', name: 'exchange_page')]
    public function init(ExchangeRepository $exchangeRepository): Response{
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $allUserExchanges = $exchangeRepository->createQueryBuilder('e')
            ->where('e.userRequesting = :user OR e.userOffering = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
        $acceptedExchanges = [];
        $pendingRequests   = []; 
        $inProgressHistory = [];         $completedHistory  = [];
        $refusedHistory    = [];
        foreach ($allUserExchanges as $exchange) {
            $status = $exchange->getStatus();
            if ($status === 'pending') {
                if ($exchange->getUserRequesting() === $user) {
                    $inProgressHistory[] = $exchange;
                }
                elseif ($exchange->getUserOffering() === $user) {
                    $pendingRequests[] = $exchange;
                }
            } elseif ($status === 'accepted') {
                $acceptedExchanges[] = $exchange;
            } elseif ($status === 'completed') {
                $completedHistory[] = $exchange;
            } elseif ($status === 'refused') {
                $refusedHistory[] = $exchange;
            }
        }
        $totalExchanges        = count($acceptedExchanges) + count($completedHistory) + count($inProgressHistory) + count($pendingRequests);
        $completedHistoryCount = count($completedHistory);
        $activeExchangesCount  = count($acceptedExchanges) + count($inProgressHistory) + count($pendingRequests);
        $successRate = $totalExchanges > 0 ? ($completedHistoryCount / $totalExchanges) * 100 : 0;

        return $this->render('exchange/exchange.html.twig', [
            'user'                  => $user,
            'acceptedExchanges'     => $acceptedExchanges,
            'pendingRequests'       => $pendingRequests,   
            'inProgressHistory'     => $inProgressHistory, 
            'completedHistory'      => $completedHistory,
            'refusedHistory'        => $refusedHistory,
            'successRate'           => round($successRate, 2),
            'totalExchanges'        => $totalExchanges,
            'completedHistoryCount' => $completedHistoryCount,
            'activeExchangesCount'  => $activeExchangesCount

        ]);

    }



    #[Route('/exchange/{id}/accept', name: 'app_exchange_accept')]
    public function accept(Exchange $exchange, EntityManagerInterface $em): Response
    {
        if ($exchange->getUserOffering() !== $this->getUser()) {
               throw $this->createAccessDeniedException("Vous n'avez pas le droit d'accepter cet échange.");}
        $exchange->setStatus('accepted');
        $em->flush();
        return $this->redirectToRoute('exchange_page');

    }



    #[Route('/exchange/{id}/decline', name: 'app_exchange_decline')]
    public function decline(Exchange $exchange, EntityManagerInterface $em): Response
    {
        if ($exchange->getUserOffering() !== $this->getUser()) {
                throw $this->createAccessDeniedException("Vous n'avez pas le droit de refuser cet échange.");}
        $exchange->setStatus('refused');
        $em->flush();
        return $this->redirectToRoute('exchange_page');

    }

    #[Route('/exchange/update-status', name: 'app_exchange_update_status', methods: ['POST'])]
    public function updateStatus(\Symfony\Component\HttpFoundation\Request $request,\App\Repository\ExchangeRepository $exchangeRepository, \Doctrine\ORM\EntityManagerInterface $em

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
    $exchange->setStatus($status);

    $em->flush();
    return new \Symfony\Component\HttpFoundation\JsonResponse(['success' => true]);

}
#[Route('/cancel-progress/{id}', name: 'app_exchange_cancel_progress')]
public function cancelProgress(Exchange $exchange, EntityManagerInterface $em): Response
{
    $user = $this->getUser();
    if (!$user) {
        $this->addFlash('error', 'You must be logged in.');
        return $this->redirectToRoute('app_login');
    }
    
    if ($exchange->getUserRequesting() !== $user && $exchange->getUserOffering() !== $user) {
        $this->addFlash('error', 'You cannot cancel this exchange.');
        return $this->redirectToRoute('exchange_page');
    }
    
    if ($exchange->getStatus() !== 'in_progress') {
        $this->addFlash('error', 'Only an exchange in progress can be cancelled.');
        return $this->redirectToRoute('exchange_page');
    }
    
    $exchange->setStatus('cancelled');
    $em->flush();
    
    $this->addFlash('success', 'Exchange cancelled successfully.');
    
    return $this->redirectToRoute('exchange_page');
}

#[Route('/complete/{id}', name: 'app_exchange_complete')]
public function complete(Exchange $exchange, EntityManagerInterface $em): Response
{
    $user = $this->getUser();
    
    if (!$user) {
        $this->addFlash('error', 'You must be logged in.');
        return $this->redirectToRoute('app_login');
    }
    
    if ($exchange->getUserRequesting() !== $user && $exchange->getUserOffering() !== $user) {
        $this->addFlash('error', 'You cannot complete this exchange.');
        return $this->redirectToRoute('exchange_page');
    }
    
    if ($exchange->getStatus() !== 'accepted') {
        $this->addFlash('error', 'Only an accepted exchange can be completed.');
        return $this->redirectToRoute('exchange_page');
    }
    
    $exchange->setStatus('completed');
    $em->flush();
    
    $this->addFlash('success', 'Exchange marked as completed successfully!');
    return $this->redirectToRoute('exchange_page');
}
}

