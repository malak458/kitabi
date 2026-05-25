<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request                     $request,
        EntityManagerInterface      $em,
        UserPasswordHasherInterface $passwordHasher
    )
    {
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

            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/login', name: 'app_login')]
    public function login(
        Request                     $request,
        UserRepository              $userRepository,
        UserPasswordHasherInterface $passwordHasher,
        SessionInterface            $session
    )
    {

        $form = $this->createForm(\App\Form\LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $email = $data['email'];
            $password = $data['password'];

            $user = $userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                return $this->render('auth/login.html.twig', [
                    'form' => $form->createView(),
                    'error' => 'Email incorrect'
                ]);
            }

            if (!$passwordHasher->isPasswordValid($user, $password)) {
                return $this->render('auth/login.html.twig', [
                    'form' => $form->createView(),
                    'error' => 'Mot de passe incorrect'
                ]);
            }

            $session->set('user_id', $user->getId());

            return $this->redirectToRoute('app_login');
        }

        return $this->render('auth/login.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(SessionInterface $session)
    {
        // 🔴 détruit toute la session
        $session->invalidate();

        // 🔵 redirection vers login
        return $this->redirectToRoute('app_login');
    }
}
