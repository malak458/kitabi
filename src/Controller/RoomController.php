<?php

namespace App\Controller;

use App\Entity\Room;
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
        // Récupération optimisée des salons pour éviter la boucle infinie
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
        
        // Initialisation automatique de la date de création du salon
        $room->setCreatedAt(new \DateTime());

        // Création et traitement du formulaire Symfony
        $form = $this->createForm(RoomType::class, $room);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            // 1. Gestion de l'upload de l'image de couverture
            $imageFile = $form->get('image')->getData();

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
                // Image par défaut si aucun fichier n'est téléversé
                $room->setImage('default-book.jpg');
            }

            // 2. Traitement manuel des tags (transformation du tableau reçu en chaîne "Tag1,Tag2")
            $tagsArray = $request->request->all('room')['tags'] ?? [];
            if (!empty($tagsArray)) {
                $room->setTags(implode(',', $tagsArray));
            }

            // 3. Sauvegarde de l'entité Room dans la base de données
            $entityManager->persist($room);
            $entityManager->flush();

            // Notification flash de succès
            $this->addFlash('success', 'Votre salon de lecture a été créé avec succès !');

            // Redirection vers le tableau de bord des salons
            return $this->redirectToRoute('app_rooms');
        }

        return $this->render('room/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}