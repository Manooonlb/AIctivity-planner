<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230517125458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_activity DROP CONSTRAINT fk_4cf9ed5aa76ed395');
        $this->addSql('ALTER TABLE user_activity DROP CONSTRAINT fk_4cf9ed5a81c06096');
        $this->addSql('DROP TABLE user_activity');
        $this->addSql('ALTER TABLE activity ADD owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE activity ADD CONSTRAINT FK_AC74095A7E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AC74095A7E3C61F9 ON activity (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE user_activity (user_id INT NOT NULL, activity_id INT NOT NULL, PRIMARY KEY(user_id, activity_id))');
        $this->addSql('CREATE INDEX idx_4cf9ed5a81c06096 ON user_activity (activity_id)');
        $this->addSql('CREATE INDEX idx_4cf9ed5aa76ed395 ON user_activity (user_id)');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT fk_4cf9ed5aa76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT fk_4cf9ed5a81c06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activity DROP CONSTRAINT FK_AC74095A7E3C61F9');
        $this->addSql('DROP INDEX IDX_AC74095A7E3C61F9');
        $this->addSql('ALTER TABLE activity DROP owner_id');
    }
}
