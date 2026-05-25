<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260525170940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room ADD scheduled_at DATETIME DEFAULT NULL, CHANGE total_pages total_pages INT NOT NULL, CHANGE type type VARCHAR(50) NOT NULL, CHANGE max_participants max_participants INT NOT NULL, CHANGE genre genre VARCHAR(100) NOT NULL, CHANGE tags tags LONGTEXT DEFAULT NULL, CHANGE description description LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE room DROP scheduled_at, CHANGE total_pages total_pages VARCHAR(255) NOT NULL, CHANGE type type VARCHAR(255) NOT NULL, CHANGE max_participants max_participants VARCHAR(255) NOT NULL, CHANGE genre genre VARCHAR(255) NOT NULL, CHANGE tags tags VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL');
    }
}
