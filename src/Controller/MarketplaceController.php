<?php

namespace App\Controller;

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

        $search    = $request->query->get('search', '');
        $genre     = $request->query->get('genre', '');
        $condition = $request->query->get('condition', '');
        $sort      = $request->query->get('sort', 'newest');

        $books = $bookRepository->findFilteredQuery(
            $search, $genre, $condition, $sort
        )->getQuery()->getResult();

        // FAVORIS
        $favoriteBookIds = [];
        if ($this->getUser()) {
            $favorites = $favoriteRepository->findByUserId($this->getUser()->getId());
            $favoriteBookIds = array_map(fn($fav) => $fav->getBook()->getId(), $favorites);
        }

        return $this->render('marketplace/index.html.twig', [
            'books'           => $books,
            'bookCount'       => count($books),
            'noResult'        => count($books) === 0,
            'favoriteBookIds' => $favoriteBookIds,

            // REVIEWS
            'reviews'       => $reviewRepository->findLatest(6),
            'reviewCount'   => $reviewRepository->count([]),
            'averageRating' => $reviewRepository->getAverageRating(),

            'filters' => [
                'search'    => $search,
                'genre'     => $genre,
                'condition' => $condition,
                'sort'      => $sort,
            ],

            'genres' => [
                'Fiction','Fantasy','Romance','Mystery','Thriller',
                'History','Biography','Science Fiction','Classic Literature'
            ],

            'conditions' => [
                'Like new','Good','Fair','Acceptable'
            ],

            'sortOptions' => [
                'newest'     => '≡ Plus récents',
                'price_asc'  => '≡ Price: low to high',
                'price_desc' => '≡ Price: high to low',
                'title_az'   => '≡ Title: A-Z',
            ],
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
        $rating  = (int) $request->request->get('rating', 0);

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
            'id'     => $book->getId(),
            'titre'  => $book->getTitre(),
            'auteur' => $book->getAuteur(),
            'prix'   => $book->getPrix(),
            'genre'  => $book->getGenre(),
        ], $books);

        return $this->json($data);
    }
}