<?php

namespace App\Controller;

use App\Repository\FavoriteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavorisController extends AbstractController
{
    private const USER_ID = 1;

    #[Route('/favoris', name: 'app_favoris')]
    public function index(FavoriteRepository $favoriteRepository): Response
    {
        $favorites = $favoriteRepository->findByUserId(self::USER_ID);

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
}