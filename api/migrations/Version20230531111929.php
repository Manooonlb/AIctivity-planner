<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531111929 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP CONSTRAINT fk_b6bd307ff675f31b');
        $this->addSql('DROP INDEX idx_b6bd307ff675f31b');
        $this->addSql('ALTER TABLE message DROP author_id');
        $this->addSql('ALTER TABLE message DROP date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE message ADD author_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT fk_b6bd307ff675f31b FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b6bd307ff675f31b ON message (author_id)');
    }
}
