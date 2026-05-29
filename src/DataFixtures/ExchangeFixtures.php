<?php

namespace App\DataFixtures;

use App\Entity\Exchange;
use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ExchangeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = new User();
        $user1->setEmail('demandeur@example.com');
        $user1->setName('Anis Belhadj');
        $user1->setPassword('password123');
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('offreur@example.com');
        $user2->setName('Sarah Radhia');
        $user2->setPassword('password123');
        $manager->persist($user2);

        $book1 = new Book();
        $book1->setTitle('Le Petit Prince');
        $book1->setAuthor('Antoine de Saint-Exupéry');
        $book1->setBookCondition('Good');
        $book1->setOwner($user1);
        $manager->persist($book1);

        $book2 = new Book();
        $book2->setTitle('1984');
        $book2->setAuthor('George Orwell');
        $book2->setBookCondition('Mint');
        $book2->setOwner($user2);
        $manager->persist($book2);


        $exchangeAccepted = new Exchange();
        $exchangeAccepted->setStatus('accepted');
        $exchangeAccepted->setCreatedAt(new \DateTimeImmutable('-5 days'));
        $exchangeAccepted->setUserRequesting($user1);
        $exchangeAccepted->setUserOffering($user2);
        $exchangeAccepted->setOfferedBook($book1);
        $exchangeAccepted->setRequestedBook($book2);
        $exchangeAccepted->setRate(0.0);
        $manager->persist($exchangeAccepted);

        
        $exchangePending = new Exchange();
        $exchangePending->setStatus('pending');
        $exchangePending->setCreatedAt(new \DateTimeImmutable('-2 days'));
        $exchangePending->setUserRequesting($user1);
        $exchangePending->setUserOffering($user2);
        $exchangePending->setOfferedBook($book1);
        $exchangePending->setRequestedBook($book2);
        $exchangePending->setRate(0.0);
        $manager->persist($exchangePending);

        
        $exchangeCompleted = new Exchange();
        $exchangeCompleted->setStatus('completed');
        $exchangeCompleted->setCreatedAt(new \DateTimeImmutable('-10 days'));
        $exchangeCompleted->setUserRequesting($user2); 
        $exchangeCompleted->setUserOffering($user1);
        $exchangeCompleted->setOfferedBook($book2);
        $exchangeCompleted->setRequestedBook($book1);
        $exchangeCompleted->setRate(4.5);
        $manager->persist($exchangeCompleted);

        $exchangeRefused = new Exchange();
        $exchangeRefused->setStatus('refused');
        $exchangeRefused->setCreatedAt(new \DateTimeImmutable('-1 day'));
        $exchangeRefused->setUserRequesting($user1);
        $exchangeRefused->setUserOffering($user2);
        $exchangeRefused->setOfferedBook($book1);
        $exchangeRefused->setRequestedBook($book2);
        $exchangeRefused->setRate(0.0);
        $manager->persist($exchangeRefused);

        
        $exchangeProgress = new Exchange();
        $exchangeProgress->setStatus('in-progress');
        $exchangeProgress->setCreatedAt(new \DateTimeImmutable('now'));
        $exchangeProgress->setUserRequesting($user2);
        $exchangeProgress->setUserOffering($user1);
        $exchangeProgress->setOfferedBook($book2);
        $exchangeProgress->setRequestedBook($book1);
        $exchangeProgress->setRate(0.0);
        $manager->persist($exchangeProgress);

        $manager->flush();
    }
}