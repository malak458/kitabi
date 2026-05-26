<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EditProfileType;

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

#[Route('/profile/edit', name: 'app_profile_edit')]
    public function edit(Request $request, EntityManagerInterface $em)
{
    $user = $this->getUser();

    $form = $this->createForm(EditProfileType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $em->flush(); // Symfony met à jour automatiquement

        return $this->redirectToRoute('app_profile');
    }

    return $this->render('auth/edit_profile.html.twig', [
        'form' => $form->createView()
    ]);
}
}

