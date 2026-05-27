<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Livre 1
        $book1 = new Book();
        $book1->setTitre('Le Seigneur des Anneaux');
        $book1->setAuteur('J.R.R. Tolkien');
        $book1->setGenre('Fantasy');
        $book1->setCondition('Très bon état');
        $book1->setPrix(19.99);
        $book1->setForExchange(true);
        $manager->persist($book1);

        // Livre 2
        $book2 = new Book();
        $book2->setTitre('1984');
        $book2->setAuteur('George Orwell');
        $book2->setGenre('Dystopie');
        $book2->setCondition('Comme neuf');
        $book2->setPrix(8.50);
        $book2->setForExchange(false);
        $manager->persist($book2);

        // Livre 3
        $book3 = new Book();
        $book3->setTitre('Le Petit Prince');
        $book3->setAuteur('Antoine de Saint-Exupéry');
        $book3->setGenre('Conte');
        $book3->setCondition('Usé');
        $book3->setPrix(4.00);
        $book3->setForExchange(true);
        $manager->persist($book3);

        // Cette commande valide et envoie tout en base de données d'un coup
        $manager->flush();
    }
}