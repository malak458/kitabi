<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Favorite;
use App\Repository\BookRepository;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    private const USER_ID = 1;

    #[Route('/marketplace', name: 'app_marketplace')]
    public function index(
        BookRepository $bookRepository,
        FavoriteRepository $favoriteRepository
    ): Response {

        $books = $bookRepository->findAll();

        $favorites = $favoriteRepository->findByUserId(self::USER_ID);

        $favoriteBookIds = array_map(
            fn($fav) => $fav->getBook()->getId(),
            $favorites
        );

        return $this->render('marketplace/index.html.twig', [
            'books' => $books,
            'favoriteBookIds' => $favoriteBookIds,
        ]);
    }

    #[Route('/book/new', name: 'app_book_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {

        if ($request->isMethod('POST')) {

            $book = new Book();

            $book->setTitre($request->request->get('titre'));
            $book->setAuteur($request->request->get('auteur'));
            $book->setGenre($request->request->get('genre'));
            $book->setCondition($request->request->get('condition'));
            $book->setPrix((float) $request->request->get('prix'));
            $book->setUser($this->getUser());

            $book->setForExchange(
                $request->request->has('for_exchange')
            );

            // IMAGE
            $imageFile = $request->files->get('image');

            if ($imageFile) {

                $newFilename =
                    uniqid() . '.' . $imageFile->guessExtension();

                try {

                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );

                    $book->setImage($newFilename);

                } catch (\Exception $e) {

                    $book->setImage('default.jpg');
                }

            } else {

                $book->setImage('default.jpg');
            }

            $entityManager->persist($book);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre livre a bien été publié !'
            );

            return $this->redirectToRoute('app_marketplace');
        }

        return $this->render('book/new.html.twig');
    }

    #[Route('/book/{id}', name: 'app_book_show')]
    public function show(Book $book): Response
    {
        return $this->render('book/details.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/book/{id}/favorite', name: 'app_book_favorite', methods: ['POST'])]
    public function toggleFavorite(
        int $id,
        BookRepository $bookRepository,
        FavoriteRepository $favoriteRepository,
        EntityManagerInterface $em,
        Request $request
    ): JsonResponse {

        if (!$request->isXmlHttpRequest()) {

            return $this->json([
                'success' => false,
                'message' => 'Requête invalide'
            ], 400);
        }

        $book = $bookRepository->find($id);

        if (!$book) {

            return $this->json([
                'success' => false,
                'message' => 'Livre introuvable'
            ], 404);
        }

        $existing =
            $favoriteRepository
                ->findOneByUserIdAndBook(
                    self::USER_ID,
                    $id
                );

        if ($existing) {

            $em->remove($existing);
            $em->flush();

            return $this->json([
                'success' => true,
                'isFavorite' => false,
                'message' => 'Retiré des favoris'
            ]);
        }

        $favorite = new Favorite();

        $favorite->setUserId(self::USER_ID);
        $favorite->setBook($book);

        $em->persist($favorite);
        $em->flush();

        return $this->json([
            'success' => true,
            'isFavorite' => true,
            'message' => 'Ajouté aux favoris'
        ]);
    }
}