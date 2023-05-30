<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230523121651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE qcm_answer DROP CONSTRAINT fk_6a83fc031e27f6bf');
        $this->addSql('DROP INDEX idx_6a83fc031e27f6bf');
        $this->addSql('ALTER TABLE qcm_answer DROP question_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE qcm_answer ADD question_id INT NOT NULL');
        $this->addSql('ALTER TABLE qcm_answer ADD CONSTRAINT fk_6a83fc031e27f6bf FOREIGN KEY (question_id) REFERENCES qcm (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_6a83fc031e27f6bf ON qcm_answer (question_id)');
    }
}
