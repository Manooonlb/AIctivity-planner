<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531113228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE conversation_id_seq CASCADE');
        $this->addSql('ALTER TABLE user_conversation DROP CONSTRAINT fk_a425aeba76ed395');
        $this->addSql('ALTER TABLE user_conversation DROP CONSTRAINT fk_a425aeb9ac0396');
        $this->addSql('DROP TABLE user_conversation');
        $this->addSql('DROP TABLE conversation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_conversation (user_id INT NOT NULL, conversation_id INT NOT NULL, PRIMARY KEY(user_id, conversation_id))');
        $this->addSql('CREATE INDEX idx_a425aeb9ac0396 ON user_conversation (conversation_id)');
        $this->addSql('CREATE INDEX idx_a425aeba76ed395 ON user_conversation (user_id)');
        $this->addSql('CREATE TABLE conversation (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE user_conversation ADD CONSTRAINT fk_a425aeba76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_conversation ADD CONSTRAINT fk_a425aeb9ac0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
