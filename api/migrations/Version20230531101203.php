<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531101203 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP CONSTRAINT fk_b6bd307f7e3c61f9');
        $this->addSql('DROP INDEX idx_b6bd307f7e3c61f9');
        $this->addSql('ALTER TABLE message ADD title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE message ADD is_read BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE message RENAME COLUMN owner_id TO recipient_id');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FE92F8F78 FOREIGN KEY (recipient_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B6BD307FE92F8F78 ON message (recipient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307FE92F8F78');
        $this->addSql('DROP INDEX IDX_B6BD307FE92F8F78');
        $this->addSql('ALTER TABLE message DROP title');
        $this->addSql('ALTER TABLE message DROP is_read');
        $this->addSql('ALTER TABLE message RENAME COLUMN recipient_id TO owner_id');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT fk_b6bd307f7e3c61f9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b6bd307f7e3c61f9 ON message (owner_id)');
    }
}
