<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AuthCheck
{
    public function isLoggedIn(SessionInterface $session): bool
    {
        return $session->get('user_id') !== null;
    }

    public function getUserId(SessionInterface $session)
    {
        return $session->get('user_id');
    }
}
