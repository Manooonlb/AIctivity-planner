<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605153333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation ADD activity_owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conversation ADD activity_participant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9DE36D1F7 FOREIGN KEY (activity_owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9114E479 FOREIGN KEY (activity_participant_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8A8E26E9DE36D1F7 ON conversation (activity_owner_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9114E479 ON conversation (activity_participant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E9DE36D1F7');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E9114E479');
        $this->addSql('DROP INDEX IDX_8A8E26E9DE36D1F7');
        $this->addSql('DROP INDEX IDX_8A8E26E9114E479');
        $this->addSql('ALTER TABLE conversation DROP activity_owner_id');
        $this->addSql('ALTER TABLE conversation DROP activity_participant_id');
    }
}
