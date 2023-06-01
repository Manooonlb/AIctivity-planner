<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531102313 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD sent_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL');
        $this->addSql('ALTER TABLE message ALTER author_id SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN message.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F438A69E4 FOREIGN KEY (sent_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B6BD307F438A69E4 ON message (sent_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F438A69E4');
        $this->addSql('DROP INDEX IDX_B6BD307F438A69E4');
        $this->addSql('ALTER TABLE message DROP sent_id');
        $this->addSql('ALTER TABLE message DROP created_at');
        $this->addSql('ALTER TABLE message ALTER author_id DROP NOT NULL');
    }
}
