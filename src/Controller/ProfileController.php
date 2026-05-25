<?php
namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\AuthCheck;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        SessionInterface $session,
        AuthCheck $authCheck,
        UserRepository $userRepository
    ) {

        // 🔴 si pas connecté → login
        if (!$authCheck->isLoggedIn($session)) {
            return $this->redirectToRoute('app_login');
        }

        // 🔵 récupérer user
        $userId = $authCheck->getUserId($session);
        $user = $userRepository->find($userId);

        if (!$user) {
            // sécurité si user supprimé de la DB
            $session->invalidate();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/profile.html.twig', [
            'user' => $user
        ]);
    }
}
