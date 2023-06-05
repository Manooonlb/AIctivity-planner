<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605130441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE conversation_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE conversation (id INT NOT NULL, activity_id INT DEFAULT NULL, activity_owner_id INT DEFAULT NULL, activity_participant_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8A8E26E981C06096 ON conversation (activity_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9DE36D1F7 ON conversation (activity_owner_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9114E479 ON conversation (activity_participant_id)');
        $this->addSql('COMMENT ON COLUMN conversation.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN conversation.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E981C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9DE36D1F7 FOREIGN KEY (activity_owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9114E479 FOREIGN KEY (activity_participant_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE message ADD conversation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F9AC0396 FOREIGN KEY (conversation_id) REFERENCES conversation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B6BD307F9AC0396 ON message (conversation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE message DROP CONSTRAINT FK_B6BD307F9AC0396');
        $this->addSql('DROP SEQUENCE conversation_id_seq CASCADE');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E981C06096');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E9DE36D1F7');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E9114E479');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP INDEX IDX_B6BD307F9AC0396');
        $this->addSql('ALTER TABLE message DROP conversation_id');
    }
}
