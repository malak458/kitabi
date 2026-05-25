<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
#[Route('/register', name: 'app_register')]
public function register(
Request $request,
EntityManagerInterface $em,
UserPasswordHasherInterface $passwordHasher
) {

$user = new User();

$form = $this->createForm(RegisterType::class, $user);

$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {

$hashedPassword = $passwordHasher->hashPassword(
$user,
$user->getPassword()
);

$user->setPassword($hashedPassword);

$em->persist($user);
$em->flush();

return $this->redirectToRoute('app_register');
}

return $this->render('auth/register.html.twig', [
'form' => $form->createView()
]);
}
}
?>
