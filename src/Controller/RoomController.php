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
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RoomController extends AbstractController
{
    #[Route('/rooms', name: 'app_rooms')]
    public function index(RoomRepository $roomRepository): Response
    {
        $lives     = $roomRepository->findByTypeWithHost('live');
    $scheduled = $roomRepository->findByTypeWithHost('scheduled');

    /** @var User|null $user */
    $user    = $this->getUser();
    $myRooms = $user ? $roomRepository->findBy(['host' => $user], ['createdAt' => 'DESC']) : [];

    return $this->render('room/index.html.twig', [
        'lives'     => $lives,
        'scheduled' => $scheduled,
        'myRooms'   => $myRooms,  // ← manquait
    ]);
    }

    #[Route('/rooms/create', name: 'app_room_create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {
        $room = new Room();
        $room->setCreatedAt(new \DateTime());

        /** @var User $user */
        $user = $this->getUser();
            if (!$user) {
            $this->addFlash('error', 'Vous devez être connecté pour créer une room.');
            return $this->redirectToRoute('app_login');
            }
            $room->setHost($user);

        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $safeFilename = $slugger->slug(
                    pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME)
                );
                $newFilename = 'room_' . $safeFilename . '_' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/rooms',
                        $newFilename
                    );
                    $room->setImage($newFilename);
                } catch (\Exception $e) {
                    $this->addFlash('error', "Erreur lors de l'upload de la couverture.");
                    $room->setImage('default-book.jpg');
                }
            } else {
                $room->setImage('default-book.jpg');
            }

           
            $tagsArray = $request->request->all('room_tags') ?? [];
            if (!empty($tagsArray)) {
                $room->setTags(implode(',', array_filter($tagsArray)));
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