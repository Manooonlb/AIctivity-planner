<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230605153055 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT fk_8a8e26e9de36d1f7');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT fk_8a8e26e9114e479');
        $this->addSql('DROP INDEX idx_8a8e26e9114e479');
        $this->addSql('DROP INDEX idx_8a8e26e9de36d1f7');
        $this->addSql('ALTER TABLE conversation DROP activity_owner_id');
        $this->addSql('ALTER TABLE conversation DROP activity_participant_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE conversation ADD activity_owner_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conversation ADD activity_participant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT fk_8a8e26e9de36d1f7 FOREIGN KEY (activity_owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT fk_8a8e26e9114e479 FOREIGN KEY (activity_participant_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8a8e26e9114e479 ON conversation (activity_participant_id)');
        $this->addSql('CREATE INDEX idx_8a8e26e9de36d1f7 ON conversation (activity_owner_id)');
    }
}
