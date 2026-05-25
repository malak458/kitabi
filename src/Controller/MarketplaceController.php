<?php

namespace App\Controller;

use App\Repository\BookRepository; // <--- IMPORTER LE REPOSITORY
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MarketplaceController extends AbstractController
{
    #[Route('/marketplace', name: 'app_marketplace')]
    public function index(BookRepository $bookRepository): Response // <--- AJOUTER L'ARGUMENT
    {
        // On demande au repository de nous donner tous les livres
        $books = $bookRepository->findAll();

        // On envoie ces livres à la page Twig
        return $this->render('marketplace/index.html.twig', [
            'books' => $books,
        ]);
    }
}