<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\FavoriteRepository;
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
        FavoriteRepository $favoriteRepository
    ): Response {

        $search    = $request->query->get('search', '');
        $genre     = $request->query->get('genre', '');
        $condition = $request->query->get('condition', '');
        $sort      = $request->query->get('sort', 'newest');

        $books = $bookRepository->findFilteredQuery(
            $search,
            $genre,
            $condition,
            $sort
        )->getQuery()->getResult();

        // IDs des livres en favoris pour l'utilisateur connecté
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

            'filters' => [
                'search'    => $search,
                'genre'     => $genre,
                'condition' => $condition,
                'sort'      => $sort,
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
                'Classic Literature',
            ],

            'conditions' => [
                'Like new',
                'Good',
                'Fair',
                'Acceptable',
            ],

            'sortOptions' => [
                'newest'     => '≡ Plus récents',
                'price_asc'  => '≡ Price: low to high',
                'price_desc' => '≡ Price: high to low',
                'title_az'   => '≡ Title: A-Z',
            ],
        ]);
    }

    #[Route('/marketplace/live-search', name: 'app_marketplace_live_search')]
    public function liveSearch(Request $request, BookRepository $bookRepository): JsonResponse
    {
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