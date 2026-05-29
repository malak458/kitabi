<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\EditProfileType;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Entity\User;

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

    #[Route('/profile/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request                $request,
        EntityManagerInterface $em,
        SluggerInterface       $slugger
    ): \Symfony\Component\HttpFoundation\Response
    {

        /** @var User $user */
        $user = $this->getUser();

        if (!$user instanceof User) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(EditProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

           
            /** @var \Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile */
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalName = pathinfo(
                    $imageFile->getClientOriginalName(),
                    PATHINFO_FILENAME
                );
                $safeName = $slugger->slug($originalName);
                $newFilename = $safeName . '-' . uniqid() . '.' . $imageFile->guessExtension();

                $uploadDir = $this->getParameter('kernel.project_dir')
                    . '/public/uploads/profiles';

             
                if ($user->getImage()) {
                    $oldPath = $uploadDir . '/' . $user->getImage();
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }

                $imageFile->move($uploadDir, $newFilename);
                $user->setImage($newFilename);  
            }
           

            $em->flush(); 

            $this->addFlash('success', 'Profil mis à jour avec succès !');
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('auth/edit_profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user,   
        ]);
    }}
