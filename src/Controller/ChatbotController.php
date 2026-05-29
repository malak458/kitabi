<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ChatbotController extends AbstractController
{
    #[Route('/chatbot', name: 'app_chatbot', methods: ['POST'])]
    public function chatbot(
        Request        $request,
        BookRepository $bookRepository
    ): JsonResponse {

        $input   = json_decode($request->getContent(), true);
        $message = trim($input['message'] ?? '');

        if ($message === '') {
            return $this->json(['error' => 'Message vide']);
        }

        $lower     = mb_strtolower($message);
        $budget    = $this->extractBudget($message);
        $genres    = $this->extractGenres($message);
        $condition = $this->extractCondition($message);

        
        if (preg_match('/\b(bonjour|salut|hello|salam|hi|bonsoir)\b/iu', $lower)) {
            return $this->json([
                'type'    => 'text',
                'message' => "Bonjour ! 👋 Je suis l'assistant KITAB.\nJe peux vous aider à :\n• Trouver un livre par genre ou auteur\n• Filtrer par budget\n• Chercher des livres à échanger\n\nQue cherchez-vous ?",
                'chips'   => ['Fiction', 'Fantasy', 'Romance', 'Sous 50 DT', 'Livres à échanger'],
            ]);
        }

      
        if (preg_match('/\b(merci|thanks|شكرا)\b/iu', $lower)) {
            return $this->json([
                'type'    => 'text',
                'message' => "Avec plaisir ! N'hésitez pas si vous cherchez autre chose 😊",
            ]);
        }

      
        if (preg_match('/échange|echang|swap|troquer/iu', $lower)) {
            $books = $this->searchBooks($bookRepository, [
                'exchange' => true,
                'genres'   => $genres,
                'budget'   => $budget,
            ]);
            return $this->json([
                'type'    => 'books',
                'message' => count($books)
                    ? "Voici les livres disponibles à l'échange :"
                    : "Aucun livre à l'échange pour le moment.",
                'books'   => array_map([$this, 'formatBook'], $books),
                'chips'   => ['Fantasy', 'Sous 30 DT'],
            ]);
        }

     
        if ($budget !== null || preg_match('/budget|prix|combien|pas\s+cher/iu', $lower)) {
            $books = $this->searchBooks($bookRepository, [
                'budget' => $budget,
                'genres' => $genres,
                'order'  => 'prix ASC',
            ]);
            $msg = $budget
                ? "Livres disponibles sous " . number_format($budget, 2) . " DT :"
                : "Voici les livres les moins chers en ce moment :";
            return $this->json([
                'type'    => 'books',
                'message' => count($books) ? $msg : "Aucun livre trouvé dans ce budget 😅",
                'books'   => array_map([$this, 'formatBook'], $books),
                'chips'   => ['Sous 30 DT', 'Sous 50 DT', 'Sous 80 DT'],
            ]);
        }

        if (!empty($genres)) {
            $books = $this->searchBooks($bookRepository, [
                'genres' => $genres,
                'budget' => $budget,
            ]);
            return $this->json([
                'type'    => 'books',
                'message' => count($books)
                    ? "Voici les livres " . implode(', ', $genres) . " disponibles :"
                    : "Aucun livre " . implode(', ', $genres) . " trouvé pour l'instant.",
                'books'   => array_map([$this, 'formatBook'], $books),
                'chips'   => ['Sous 40 DT', 'Livres à échanger'],
            ]);
        }

       
        if ($condition !== null) {
            $books = $this->searchBooks($bookRepository, [
                'condition' => $condition,
            ]);
            return $this->json([
                'type'    => 'books',
                'message' => count($books)
                    ? "Livres en état \"" . $condition . "\" :"
                    : "Aucun livre trouvé en état \"" . $condition . "\" 😅",
                'books'   => array_map([$this, 'formatBook'], $books),
                'chips'   => ['Sous 50 DT', 'Fantasy', 'Romance'],
            ]);
        }

       
        $keywords = preg_replace(
            '/\b(je|cherche|trouve|un|une|le|la|les|du|de|des|livre|livres|par|auteur)\b/iu',
            '',
            $lower
        );
        $keywords = trim(preg_replace('/\s+/', ' ', $keywords));

        if ($keywords !== '') {
            $books = $this->searchBooks($bookRepository, ['search' => $keywords]);
            if (count($books) > 0) {
                return $this->json([
                    'type'    => 'books',
                    'message' => "Résultats pour « " . $keywords . " » :",
                    'books'   => array_map([$this, 'formatBook'], $books),
                    'chips'   => ['Sous 50 DT', 'Fantasy', 'Livres à échanger'],
                ]);
            }
        }

     
        $books = $this->searchBooks($bookRepository, ['order' => 'id DESC', 'limit' => 10]);
        return $this->json([
            'type'    => 'books',
            'message' => "Je n'ai pas bien compris, mais voici nos dernières arrivées 📚",
            'books'   => array_map([$this, 'formatBook'], $books),
            'chips'   => ['Fiction', 'Fantasy', 'Sous 50 DT', 'Livres à échanger'],
        ]);
    }

  
    private function searchBooks(BookRepository $repo, array $opts): array
    {
        $qb = $repo->createQueryBuilder('b');

     
        if (!empty($opts['genres'])) {
            $orX = $qb->expr()->orX();
            foreach ($opts['genres'] as $i => $genre) {
                $orX->add($qb->expr()->eq('b.genre', ':genre' . $i));
                $qb->setParameter('genre' . $i, $genre);
            }
            $qb->andWhere($orX);
        }

      
        if (!empty($opts['budget'])) {
            $qb->andWhere('b.prix <= :budget')
               ->setParameter('budget', $opts['budget']);
        }

       
        if (!empty($opts['condition'])) {
            $qb->andWhere('b.condition = :condition')
               ->setParameter('condition', $opts['condition']);
        }

       
        if (!empty($opts['exchange'])) {
            $qb->andWhere('b.forExchange = :exch')
               ->setParameter('exch', true);
        }

     
        if (!empty($opts['search'])) {
            $qb->andWhere('b.titre LIKE :s OR b.auteur LIKE :s')
               ->setParameter('s', '%' . $opts['search'] . '%');
        }

      
        $order = $opts['order'] ?? 'id DESC';
        [$field, $dir] = explode(' ', $order);
        $qb->orderBy('b.' . $field, $dir);

      
        $qb->setMaxResults($opts['limit'] ?? 100);

        return $qb->getQuery()->getResult();
    }

 
    private function extractBudget(string $text): ?float
    {
       
        if (preg_match(
            '/(?:sous|moins\s+de|max\.?\s*|maximum\s*|<\s*)(\d+(?:[.,]\d+)?)\s*(?:dt|dinar|tnd)?/iu',
            $text, $m
        )) {
            return (float) str_replace(',', '.', $m[1]);
        }
      
        if (preg_match(
            '/(\d+(?:[.,]\d+)?)\s*(?:dt|dinar|tnd)/iu',
            $text, $m
        )) {
            return (float) str_replace(',', '.', $m[1]);
        }
        return null;
    }


    private function extractGenres(string $text): array
    {
        $map = [
            'fiction'         => 'Fiction',
            'fantasy'         => 'Fantasy',
            'fantastique'     => 'Fantasy',
            'romance'         => 'Romance',
            'amour'           => 'Romance',
            'mystery'         => 'Mystery',
            'mystère'         => 'Mystery',
            'polar'           => 'Mystery',
            'thriller'        => 'Thriller',
            'histoire'        => 'History',
            'history'         => 'History',
            'biographie'      => 'Biography',
            'biography'       => 'Biography',
            'science.fiction' => 'Science Fiction',
            'sci-fi'          => 'Science Fiction',
            'sf'              => 'Science Fiction',
            'classique'       => 'Classic Literature',
            'classic'         => 'Classic Literature',
        ];

        $found = [];
        $lower = mb_strtolower($text);
        foreach ($map as $keyword => $genre) {
            if (preg_match('/' . preg_quote($keyword, '/') . '/u', $lower)) {
                $found[$genre] = true;
            }
        }
        return array_keys($found);
    }


    private function extractCondition(string $text): ?string
    {
        $lower = mb_strtolower($text);

        if (preg_match('/\bneuf\b|comme\s+neuf|like[\s-]new/iu', $lower)) return 'Neuf';
        if (preg_match('/\bbon\b/iu', $lower))                             return 'Bon';
        if (preg_match('/\bmoyen\b/iu', $lower))                           return 'Moyen';
        if (preg_match('/\bacceptable\b/iu', $lower))                      return 'Acceptable';

        return null;
    }

   
    private function formatBook(Book $book): array
    {
        return [
            'id'           => $book->getId(),
            'titre'        => $book->getTitre(),
            'auteur'       => $book->getAuteur() ?? 'Inconnu',
            'prix'         => number_format((float) $book->getPrix(), 2) . ' DT',
            'genre'        => $book->getGenre() ?? '',
            'condition'    => $book->getCondition() ?? '',
            'image'        => $book->getImage() ?? '',
            'for_exchange' => $book->isForExchange(),
        ];
    }
}