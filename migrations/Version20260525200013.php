<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260525200013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_68C58ED916A2B381 (book_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE user_book (user_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_B164EFF8A76ED395 (user_id), INDEX IDX_B164EFF816A2B381 (book_id), PRIMARY KEY (user_id, book_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE favorite ADD CONSTRAINT FK_68C58ED916A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_book ADD CONSTRAINT FK_B164EFF816A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY `FK_CBE5A331A76ED395`');
        $this->addSql('DROP INDEX IDX_CBE5A331A76ED395 ON book');
        $this->addSql('ALTER TABLE book ADD genre VARCHAR(255) NOT NULL, ADD `condition` VARCHAR(255) NOT NULL, DROP conditions, DROP description, DROP user_id, CHANGE prix prix NUMERIC(10, 2) NOT NULL, CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE for_exchange for_exchange TINYINT NOT NULL');
        $this->addSql('ALTER TABLE room ADD scheduled_at DATETIME DEFAULT NULL, CHANGE total_pages total_pages INT NOT NULL, CHANGE type type VARCHAR(50) NOT NULL, CHANGE max_participants max_participants INT NOT NULL, CHANGE genre genre VARCHAR(100) NOT NULL, CHANGE tags tags LONGTEXT DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite DROP FOREIGN KEY FK_68C58ED916A2B381');
        $this->addSql('ALTER TABLE user_book DROP FOREIGN KEY FK_B164EFF8A76ED395');
        $this->addSql('ALTER TABLE user_book DROP FOREIGN KEY FK_B164EFF816A2B381');
        $this->addSql('DROP TABLE favorite');
        $this->addSql('DROP TABLE user_book');
        $this->addSql('ALTER TABLE book ADD conditions VARCHAR(255) NOT NULL, ADD description VARCHAR(255) NOT NULL, ADD user_id INT DEFAULT NULL, DROP genre, DROP `condition`, CHANGE prix prix VARCHAR(255) NOT NULL, CHANGE for_exchange for_exchange VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT `FK_CBE5A331A76ED395` FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331A76ED395 ON book (user_id)');
        $this->addSql('ALTER TABLE room DROP scheduled_at, CHANGE total_pages total_pages VARCHAR(255) NOT NULL, CHANGE type type VARCHAR(255) NOT NULL, CHANGE max_participants max_participants VARCHAR(255) NOT NULL, CHANGE genre genre VARCHAR(255) NOT NULL, CHANGE tags tags VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL');
    }
}
