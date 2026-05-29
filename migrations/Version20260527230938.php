<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260527230938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY `FK_BOOK_USER`');
        $this->addSql('DROP INDEX fk_book_user ON book');
        $this->addSql('CREATE INDEX IDX_CBE5A331A76ED395 ON book (user_id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT `FK_BOOK_USER` FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP FOREIGN KEY FK_CBE5A331A76ED395');
        $this->addSql('DROP INDEX idx_cbe5a331a76ed395 ON book');
        $this->addSql('CREATE INDEX FK_BOOK_USER ON book (user_id)');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE SET NULL');
    }
}
