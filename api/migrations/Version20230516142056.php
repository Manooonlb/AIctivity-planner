<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516142056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation_user DROP CONSTRAINT fk_5aecb5559ac0396');
        $this->addSql('ALTER TABLE conversation_user DROP CONSTRAINT fk_5aecb555a76ed395');
        $this->addSql('DROP TABLE conversation_user');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT fk_b6bd307f9ac0396');
        $this->addSql('DROP INDEX idx_b6bd307f9ac0396');
        $this->addSql('ALTER TABLE message DROP conversation_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE conversation_user (conversation_id INT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(conversation_id, user_id))');
        $this->addSql('CREATE INDEX idx_5aecb555a76ed395 ON conversation_user (user_id)');
        $this->addSql('CREATE INDEX idx_5aecb5559ac0396 ON conversation_user (conversation_id)');
        $this->addSql('ALTER TABLE conversation_user ADD CONSTRAINT fk_5aecb5559ac0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation_user ADD CONSTRAINT fk_5aecb555a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD conversation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT fk_b6bd307f9ac0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b6bd307f9ac0396 ON message (conversation_id)');
    }
}
