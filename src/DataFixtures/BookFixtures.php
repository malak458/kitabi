<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $books = [

            [
                'titre' => 'Dix Petits Nègres',
                'auteur' => 'Agatha Christie',
                'genre' => 'Classic',
                'condition' => 'Très bon état',
                'prix' => 35,
                'exchange' => true,
                'image' => 'dix-petits-negres.webp'
            ],

            [
                'titre' => 'Tu comprendras quand tu seras plus grande',
                'auteur' => 'Virginie Grimaldi',
                'genre' => 'Romance',
                'condition' => 'Comme neuf',
                'prix' => 42,
                'exchange' => true,
                'image' => 'tu-comprendras.jpg'
            ],

            [
                'titre' => 'Le Petit Prince',
                'auteur' => 'Saint-Exupéry',
                'genre' => 'Fiction',
                'condition' => 'Bon état',
                'prix' => 25,
                'exchange' => true,
                'image' => 'le-petit-prince.jpg'
            ],

            [
                'titre' => 'Mort sur le Nil',
                'auteur' => 'Agatha Christie',
                'genre' => 'Classic',
                'condition' => 'Très bon état',
                'prix' => 37,
                'exchange' => false,
                'image' => 'mort-sur-le-nil.jpg'
            ],

            [
                'titre' => 'أن تبقى',
                'auteur' => 'خولة حمدي',
                'genre' => 'Romance',
                'condition' => 'Neuf',
                'prix' => 55,
                'exchange' => true,
                'image' => 'anthabaki.png'
            ],

            [
                'titre' => 'Pride and Prejudice',
                'auteur' => 'Jane Austen',
                'genre' => 'Classic Literature',
                'condition' => 'Acceptable',
                'prix' => 29.7,
                'exchange' => true,
                'image' => 'pride-prejudice.jpg'
            ],
        ];

        foreach ($books as $data) {

            $book = new Book();

            $book->setTitre($data['titre']);
            $book->setAuteur($data['auteur']);
            $book->setGenre($data['genre']);
            $book->setCondition($data['condition']);
            $book->setPrix($data['prix']);
            $book->setForExchange($data['exchange']);

          
            $book->setImage($data['image']);

            $manager->persist($book);
        }

        $manager->flush();
    }
}