<?php

namespace App\Controller;

use App\Entity\Room;
use App\Entity\User;
use App\Form\RoomType;
use App\Repository\RoomRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RoomController extends AbstractController
{
    #[Route('/rooms', name: 'app_rooms')]
    public function index(RoomRepository $roomRepository): Response
    {
        $lives = $roomRepository->findByTypeWithHost('live');
        $scheduled = $roomRepository->findByTypeWithHost('scheduled');

        return $this->render('room/index.html.twig', [
            'lives' => $lives,
            'scheduled' => $scheduled,
        ]);
    }

    #[Route('/rooms/create', name: 'app_room_create')]
    public function create(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $room = new Room();
        $room->setCreatedAt(new \DateTime());
        
        // Récupérer l'utilisateur connecté
       // /** @var User $user */
        //$user = $this->getUser();
        
        //if (!$user) {
            //$this->addFlash('error', 'Vous devez être connecté pour créer une room.');
            //return $this->redirectToRoute('app_rooms');
        
        
        // Assigner l'utilisateur
       // $room->setHost($user);
       $user = $entityManager->getRepository(User::class)->find(1);
    
    if (!$user) {
        $this->addFlash('error', 'Aucun utilisateur trouvé. Veuillez d\'abord créer un utilisateur.');
        return $this->redirectToRoute('app_rooms');
    }
    
    $room->setHost($user);

        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // Gestion de l'image
            $imageFile = $form->get('imageFile')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = 'room_' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/rooms',
                        $newFilename
                    );
                    $room->setImage($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', "Erreur lors de l'upload de la couverture.");
                }
            } else {
                $room->setImage('default-book.jpg');
            }

            // Gestion des tags
            $tagsArray = $request->request->all('room')['tags'] ?? [];
            if (!empty($tagsArray)) {
                $room->setTags(implode(',', $tagsArray));
            }

            $entityManager->persist($room);
            $entityManager->flush();

            $this->addFlash('success', 'Votre salon de lecture a été créé avec succès !');
            return $this->redirectToRoute('app_rooms');
        }

        return $this->render('room/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}