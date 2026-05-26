<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
#[Route('/profile', name: 'app_profile')]
public function index()
{
$user = $this->getUser();
;



return $this->render('auth/profile.html.twig', [
'user' => $user,
]);
}
}
