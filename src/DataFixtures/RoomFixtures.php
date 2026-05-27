<?php

namespace App\DataFixtures;

use App\Entity\Room;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RoomFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // ==========================================
        // 1. UTILISATEURS
        // ==========================================

        $user1 = new User();
        $user1->setNom('Test');
        $user1->setPrenom('User');
        $user1->setEmail('test@kitabi.com');
        $user1->setRoles([]);
        $user1->setPassword($this->hasher->hashPassword($user1, 'test123'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setNom('Malak');
        $user2->setPrenom('jlassi');
        $user2->setEmail('malak@gmail.com');
        $user2->setRoles([]);
        $user2->setPassword($this->hasher->hashPassword($user2, 'password123'));
        $manager->persist($user2);

        $user3 = new User();
        $user3->setNom('doua');
        $user3->setPrenom('jlassi');
        $user3->setEmail('doua@gmail.com');
        $user3->setRoles([]);
        $user3->setPassword($this->hasher->hashPassword($user3, 'password123'));
        $manager->persist($user3);

        // ==========================================
        // 2. SALLES DE LECTURE (ROOMS)
        // ==========================================

        // Room 1 : Dix Petits Nègres (Agatha Christie)
        $room1 = new Room();
        $room1->setTitre('Dix Petits Nègres');
        $room1->setAuteur('Agatha Christie');
        $room1->setTotalPages(120);
        $room1->setType('live');
        $room1->setMaxParticipants(15);
        $room1->setGenre('Classic');
        $room1->setTags(null);
        $room1->setDescription('Dix petits nègres est le titre initial francophone de l\'un des romans policiers les plus célèbres d\'Agatha Christie, publié en 1939.');
        // Liaison avec le fichier de ta capture
        $room1->setImage('room_6a148a0ce24f1.jpg'); 
        $room1->setCreatedAt(new \DateTime('2026-05-25 19:42:36'));
        $room1->setHost($user1);
        $manager->persist($room1);

        // Room 2 : Tu comprendras quand tu seras plus grande (Virginie Grimaldi)
        $room2 = new Room();
        $room2->setTitre('Tu comprendras quand tu seras plus grande');
        $room2->setAuteur('Virginie Grimaldi');
        $room2->setTotalPages(400);
        $room2->setType('live');
        $room2->setMaxParticipants(14);
        $room2->setGenre('Romance');
        $room2->setTags(null);
        $room2->setDescription('Roman contemporain de type feel-good qui traite de la résilience, du deuil et des liens intergénérationnels.');
        // Liaison avec le fichier de ta capture
        $room2->setImage('room_6a148d5f63ac4.jpg'); 
        $room2->setCreatedAt(new \DateTime('2026-05-25 19:56:46'));
        $room2->setHost($user1);
        $manager->persist($room2);

        // Room 3 : Le Petit Prince (Saint-Exupéry)
        $room3 = new Room();
        $room3->setTitre('Le Petit Prince');
        $room3->setAuteur('Saint-Exupéry');
        $room3->setTotalPages(219);
        $room3->setType('scheduled');
        $room3->setMaxParticipants(8);
        $room3->setGenre('Fiction');
        $room3->setTags('Fiction');
        $room3->setDescription('Le Petit Prince d\'Antoine de Saint-Exupéry est le deuxième livre le plus traduit au monde après la Bible.');
        // Liaison avec le fichier de ta capture
        $room3->setImage('room_le-petit-prince_6a16ab7835ad6.jpg'); 
        $room3->setCreatedAt(new \DateTime('2026-05-27 10:29:43'));
        $room3->setHost($user1);
        $manager->persist($room3);

        // Room 4 : Mort sur le Nil (Agatha Christie)
        $room4 = new Room();
        $room4->setTitre('Mort sur le Nil');
        $room4->setAuteur('Agatha Christie');
        $room4->setTotalPages(123);
        $room4->setType('live');
        $room4->setMaxParticipants(12);
        $room4->setGenre('Classic');
        $room4->setTags('Classic,Philosophy');
        $room4->setDescription('Linnet Ridgeway, une riche et belle héritière américaine, épouse Simon Doyle. Hercule Poirot doit élucider ce crime complexe.');
        // Liaison avec le fichier de ta capture
        $room4->setImage('room_MORT_6a16ad06a7d50.jpg'); 
        $room4->setCreatedAt(new \DateTime('2026-05-27 10:36:22'));
        $room4->setHost($user1);
        $manager->persist($room4);

        // Room 5 : ان تبقى (خولة حمدي)
        $room5 = new Room();
        $room5->setTitre('أن تبقى');
        $room5->setAuteur('خولة حمدي');
        $room5->setTotalPages(449);
        $room5->setType('live');
        $room5->setMaxParticipants(10);
        $room5->setGenre('Classic');
        $room5->setTags('Classic,Romance');
        $room5->setDescription('رواية شهيرة للكاتبة التونسية الدكتورة خولة حمدي، تناقش قضايا الهجرة غير الشرعية وصراع الهوية.');
        // Liaison avec le fichier de ta capture
        $room5->setImage('room_30738859_6a16c037e7967.jpg'); 
        $room5->setCreatedAt(new \DateTime('2026-05-27 11:58:15'));
        $room5->setHost($user2);
        $manager->persist($room5);

        // Room 6 : Piège pour Cendrillon (Sébastien Japrisot)
        $room6 = new Room();
        $room6->setTitre('Piège pour Cendrillon');
        $room6->setAuteur('Sébastien Japrisot');
        $room6->setTotalPages(145);
        $room6->setType('scheduled');
        $room6->setMaxParticipants(15);
        $room6->setGenre('Classic');
        $room6->setTags('Classic,Philosophy');
        $room6->setDescription('Roman policier psychologique récompensé par le Grand prix de la littérature policière en 1963.');
        // Liaison avec le fichier de ta capture
        $room6->setImage('room_81Jrt-Cri-L-AC-UF1000-1000-QL80_6a16c4cf9a323.jpg'); 
        $room6->setCreatedAt(new \DateTime('2026-05-27 12:17:50'));
        $room6->setHost($user3);
        $manager->persist($room6);

        $manager->flush();
    }
}