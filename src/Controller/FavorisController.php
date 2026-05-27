<?php

namespace App\Controller;

use App\Entity\Favorite;
use App\Repository\BookRepository;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FavorisController extends AbstractController
{
    #[Route('/favoris', name: 'app_favoris')]
    #[IsGranted('ROLE_USER')]
    public function index(FavoriteRepository $favoriteRepository): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $favorites = $favoriteRepository->findByUserId($user->getId());

        $totalFavorites = count($favorites);

        $totalValue = array_sum(
            array_map(fn($fav) => $fav->getBook()->getPrix() ?? 0, $favorites)
        );

        $exchangeCount = count(array_filter(
            $favorites,
            fn($fav) => $fav->getBook()->isForExchange()
        ));

        $genres = array_unique(
            array_map(fn($fav) => $fav->getBook()->getGenre(), $favorites)
        );

        return $this->render('favoris/index.html.twig', [
            'favBooks'       => $favorites,
            'totalFavorites' => $totalFavorites,
            'totalValue'     => $totalValue,
            'exchangeCount'  => $exchangeCount,
            'genresCount'    => count($genres),
        ]);
    }

    #[Route('/book/{id}/favorite', name: 'app_book_favorite', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
public function toggleFavorite(
    int $id,
    BookRepository $bookRepository,
    FavoriteRepository $favoriteRepository,
    EntityManagerInterface $em
): JsonResponse {
    /** @var \App\Entity\User $user */
    $user = $this->getUser();
    $book = $bookRepository->find($id);

    if (!$book) {
        return $this->json(['success' => false, 'message' => 'Livre introuvable'], 404);
    }

    $existing = $favoriteRepository->findOneBy([
        'userId' => $user->getId(),
        'book'   => $book,
    ]);

    if ($existing) {
        $em->remove($existing);
        $em->flush();
        return $this->json(['success' => true, 'action' => 'removed']);
    } else {
        $favorite = new Favorite();
        $favorite->setUserId($user->getId());
        $favorite->setBook($book);
        $em->persist($favorite);
        $em->flush();
        return $this->json(['success' => true, 'action' => 'added']);
    }
}
        }

