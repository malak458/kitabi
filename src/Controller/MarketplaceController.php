<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Exchange;
use App\Entity\Review;
use App\Repository\BookRepository;
use App\Repository\FavoriteRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MarketplaceController extends AbstractController
{
    #[Route('/marketplace', name: 'app_marketplace')]
    public function index(
        Request $request,
        BookRepository $bookRepository,
        FavoriteRepository $favoriteRepository,
        ReviewRepository $reviewRepository
    ): Response {

        $search = $request->query->get('search', '');
        $genre = $request->query->get('genre', '');
        $condition = $request->query->get('condition', '');
        $sort = $request->query->get('sort', 'newest');

        $books = $bookRepository->findFilteredQuery(
            $search,
            $genre,
            $condition,
            $sort
        )->getQuery()->getResult();

        $favoriteBookIds = [];

        if ($this->getUser()) {
            $favorites = $favoriteRepository->findByUserId($this->getUser()->getId());

            $favoriteBookIds = array_map(
                fn($fav) => $fav->getBook()->getId(),
                $favorites
            );
        }

        $myBooks = [];

        if ($this->getUser()) {
            $myBooks = $bookRepository->findBy([
                'user' => $this->getUser()
            ]);
        }

        return $this->render('marketplace/index.html.twig', [
            'books' => $books,
            'bookCount' => count($books),
            'noResult' => count($books) === 0,
            'favoriteBookIds' => $favoriteBookIds,

            'reviews' => $reviewRepository->findLatest(6),
            'reviewCount' => $reviewRepository->count([]),
            'averageRating' => $reviewRepository->getAverageRating(),

            'filters' => [
                'search' => $search,
                'genre' => $genre,
                'condition' => $condition,
                'sort' => $sort,
            ],

            'genres' => [
                'Fiction',
                'Fantasy',
                'Romance',
                'Mystery',
                'Thriller',
                'History',
                'Biography',
                'Science Fiction',
                'Classic Literature'
            ],

            'conditions' => [
                'Like new',
                'Good',
                'Fair',
                'Acceptable'
            ],

            'sortOptions' => [
                'newest' => '≡ Plus récents',
                'price_asc' => '≡ Price: low to high',
                'price_desc' => '≡ Price: high to low',
                'title_az' => '≡ Title: A-Z',
            ],

            'myBooks' => $myBooks,
        ]);
    }

    #[Route('/marketplace/review', name: 'app_marketplace_review', methods: ['POST'])]
    public function submitReview(
        Request $request,
        EntityManagerInterface $em
    ): Response {

        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $content = trim($request->request->get('content', ''));
        $rating = (int) $request->request->get('rating', 0);

        if ($content !== '' && $rating >= 1 && $rating <= 5) {

            $review = new Review();
            $review->setContent($content);
            $review->setRating($rating);
            $review->setUser($this->getUser());

            $em->persist($review);
            $em->flush();

            $this->addFlash('success', 'Merci pour votre avis !');

        } else {

            $this->addFlash('error', 'Veuillez remplir tous les champs.');
        }

        return $this->redirectToRoute('app_marketplace');
    }

    #[Route('/marketplace/live-search', name: 'app_marketplace_live_search')]
    public function liveSearch(
        Request $request,
        BookRepository $bookRepository
    ): JsonResponse {

        $q = $request->query->get('q', '');

        if (strlen($q) < 2) {
            return $this->json([]);
        }

        $books = $bookRepository->findBySearch($q);

        $data = array_map(fn($book) => [
            'id' => $book->getId(),
            'titre' => $book->getTitre(),
            'auteur' => $book->getAuteur(),
            'prix' => $book->getPrix(),
            'genre' => $book->getGenre(),
        ], $books);

        return $this->json($data);
    }

    #[Route('/exchange/create/{id}', name: 'app_exchange_create', methods: ['POST'])]
    public function create(
        Book $requestedBook,
        Request $request,
        BookRepository $bookRepository,
        EntityManagerInterface $em
    ): Response {

        $user = $this->getUser();

        if (!$user) {
            $this->addFlash(
                'error',
                'Vous devez être connecté pour proposer un échange.'
            );

            return $this->redirectToRoute('app_login');
        }

        if (!$requestedBook) {
            $this->addFlash('error', 'Livre non trouvé.');

            return $this->redirectToRoute('app_marketplace');
        }

        $bookOwner = $requestedBook->getUser();

        if (!$bookOwner) {
            $this->addFlash(
                'error',
                'Ce livre n\'a pas de propriétaire associé.'
            );

            return $this->redirectToRoute('app_marketplace');
        }

        if ($bookOwner->getId() === $user->getId()) {
            $this->addFlash(
                'error',
                'Vous ne pouvez pas échanger un livre avec vous-même !'
            );

            return $this->redirectToRoute('app_marketplace');
        }

        $mode = $request->request->get('book_mode');
        $offeredBook = null;

        if ($mode === 'new') {

            $title = trim(
                $request->request->get('new_book_title')
            );

            $author = trim(
                $request->request->get('new_book_author')
            );

            if (empty($title)) {

                $this->addFlash(
                    'error',
                    'Le titre du livre est obligatoire.'
                );

                return $this->redirectToRoute('app_marketplace');
            }

            $offeredBook = new Book();

            $offeredBook->setTitre($title);

            $offeredBook->setAuteur(
                !empty($author)
                    ? $author
                    : 'Auteur inconnu'
            );

            $offeredBook->setUser($user);
            $offeredBook->setForExchange(true);
            $offeredBook->setPrix(0);
            $offeredBook->setCondition('Bon');
            $offeredBook->setGenre('Divers');
            

            $em->persist($offeredBook);

        } else {

            $offeredBookId = $request->request->get(
                'offered_book_id'
            );

            if (empty($offeredBookId)) {

                $this->addFlash(
                    'error',
                    'Veuillez sélectionner un livre à échanger.'
                );

                return $this->redirectToRoute('app_marketplace');
            }

            $offeredBook = $bookRepository->find(
                $offeredBookId
            );

            if (!$offeredBook) {

                $this->addFlash(
                    'error',
                    'Livre proposé non trouvé.'
                );

                return $this->redirectToRoute('app_marketplace');
            }

            if (
                $offeredBook->getUser()->getId()
                !== $user->getId()
            ) {

                $this->addFlash(
                    'error',
                    'Vous ne pouvez proposer qu\'un de vos propres livres.'
                );

                return $this->redirectToRoute('app_marketplace');
            }
        }

        if (!$offeredBook) {

            $this->addFlash(
                'error',
                'Erreur technique lors de la création de l\'échange.'
            );

            return $this->redirectToRoute('app_marketplace');
        }

        $exchange = new Exchange();

        $exchange->setUserRequesting($user);
        $exchange->setUserOffering($bookOwner);
        $exchange->setRequestedBook($requestedBook);
        $exchange->setOfferedBook($offeredBook);
        $exchange->setStatus('pending');
        $exchange->setCreatedAt(
            new \DateTimeImmutable()
        );

        $em->persist($exchange);
        $em->flush();

        $this->addFlash(
            'success',
            'Votre demande d\'échange a bien été envoyée !'
        );

        return $this->redirectToRoute('app_marketplace');
    }
}