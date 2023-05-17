<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230517130031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_activity (user_id INT NOT NULL, activity_id INT NOT NULL, PRIMARY KEY(user_id, activity_id))');
        $this->addSql('CREATE INDEX IDX_4CF9ED5AA76ED395 ON user_activity (user_id)');
        $this->addSql('CREATE INDEX IDX_4CF9ED5A81C06096 ON user_activity (activity_id)');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT FK_4CF9ED5AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_activity ADD CONSTRAINT FK_4CF9ED5A81C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_activity DROP CONSTRAINT FK_4CF9ED5AA76ED395');
        $this->addSql('ALTER TABLE user_activity DROP CONSTRAINT FK_4CF9ED5A81C06096');
        $this->addSql('DROP TABLE user_activity');
    }
}
