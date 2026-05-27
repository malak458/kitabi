<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260527193008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, auteur VARCHAR(255) NOT NULL, genre VARCHAR(255) NOT NULL, `condition` VARCHAR(255) NOT NULL, prix NUMERIC(10, 2) NOT NULL, for_exchange TINYINT NOT NULL, image VARCHAR(255) DEFAULT NULL, user_id INT DEFAULT NULL, INDEX IDX_CBE5A331A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE exchange (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, user_requesting_id INT NOT NULL, user_offering_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_D33BB079BE6B186C (user_requesting_id), INDEX IDX_D33BB07927C4BD65 (user_offering_id), INDEX IDX_D33BB07916A2B381 (book_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE favorite (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_68C58ED916A2B381 (book_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, auteur VARCHAR(255) NOT NULL, total_pages INT NOT NULL, type VARCHAR(50) NOT NULL, max_participants INT NOT NULL, genre VARCHAR(100) NOT NULL, tags LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, scheduled_at DATETIME DEFAULT NULL, host_id INT NOT NULL, INDEX IDX_729F519B1FB8D185 (host_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, image VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, bio LONGTEXT DEFAULT NULL, roles JSON NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_book (user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_B164EFF8A76ED395 (user_id), INDEX IDX_B164EFF816A2B381 (book_id), PRIMARY KEY (user_id, book_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB079BE6B186C FOREIGN KEY (user_requesting_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB07927C4BD65 FOREIGN KEY (user_offering_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB07916A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED916A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B1FB8D185 FOREIGN KEY (host_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331A76ED395');
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB079BE6B186C');
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB07927C4BD65');
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB07916A2B381');
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED916A2B381');
        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B1FB8D185');
        $this->addSql('ALTER TABLE user_book DROP FOREIGN KEY FK_B164EFF8A76ED395');
        $this->addSql('ALTER TABLE user_book DROP FOREIGN KEY FK_B164EFF816A2B381');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE exchange');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_book');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
