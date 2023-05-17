<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516142710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_conversation (user_id INT NOT NULL, conversation_id INT NOT NULL, PRIMARY KEY(user_id, conversation_id))');
        $this->addSql('CREATE INDEX IDX_A425AEBA76ED395 ON user_conversation (user_id)');
        $this->addSql('CREATE INDEX IDX_A425AEB9AC0396 ON user_conversation (conversation_id)');
        $this->addSql('ALTER TABLE user_conversation ADD CONSTRAINT FK_A425AEBA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_conversation ADD CONSTRAINT FK_A425AEB9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD conversation_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD owner_id INT NOT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B6BD307F9AC0396 ON message (conversation_id)');
        $this->addSql('CREATE INDEX IDX_B6BD307F7E3C61F9 ON message (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_conversation DROP CONSTRAINT FK_A425AEBA76ED395');
        $this->addSql('ALTER TABLE user_conversation DROP CONSTRAINT FK_A425AEB9AC0396');
        $this->addSql('DROP TABLE user_conversation');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F7E3C61F9');
        $this->addSql('DROP INDEX IDX_B6BD307F9AC0396');
        $this->addSql('DROP INDEX IDX_B6BD307F7E3C61F9');
        $this->addSql('ALTER TABLE message DROP conversation_id');
        $this->addSql('ALTER TABLE message DROP owner_id');
    }
}
