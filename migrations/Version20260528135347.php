<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260528135347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY `FK_D33BB07916A2B381`');
        $this->addSql('DROP INDEX IDX_D33BB07916A2B381 ON exchange');
        $this->addSql('ALTER TABLE exchange ADD rate DOUBLE PRECISION DEFAULT NULL, ADD requested_book_id INT NOT NULL, CHANGE book_id offered_book_id INT NOT NULL');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB0793C5A26A6 FOREIGN KEY (offered_book_id) REFERENCES book (id)');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT FK_D33BB079D46EAAC5 FOREIGN KEY (requested_book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_D33BB0793C5A26A6 ON exchange (offered_book_id)');
        $this->addSql('CREATE INDEX IDX_D33BB079D46EAAC5 ON exchange (requested_book_id)');
        $this->addSql('ALTER TABLE user ADD rating DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB0793C5A26A6');
        $this->addSql('ALTER TABLE exchange DROP FOREIGN KEY FK_D33BB079D46EAAC5');
        $this->addSql('DROP INDEX IDX_D33BB0793C5A26A6 ON exchange');
        $this->addSql('DROP INDEX IDX_D33BB079D46EAAC5 ON exchange');
        $this->addSql('ALTER TABLE exchange ADD book_id INT NOT NULL, DROP rate, DROP offered_book_id, DROP requested_book_id');
        $this->addSql('ALTER TABLE exchange ADD CONSTRAINT `FK_D33BB07916A2B381` FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_D33BB07916A2B381 ON exchange (book_id)');
        $this->addSql('ALTER TABLE user DROP rating');
    }
}
