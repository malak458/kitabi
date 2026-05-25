<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260525095936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE exchange (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, user_requesting_id INT NOT NULL, user_offering_id INT NOT NULL, book_id INT NOT NULL, INDEX IDX_D33BB079BE6B186C (user_requesting_id), INDEX IDX_D33BB07927C4BD65 (user_offering_id), INDEX IDX_D33BB07916A2B381 (book_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB079BE6B186C FOREIGN KEY (user_requesting_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB07927C4BD65 FOREIGN KEY (user_offering_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB07916A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB079BE6B186C');
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB07927C4BD65');
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB07916A2B381');
        $this->addSql('DROP TABLE exchange');
    }
}
