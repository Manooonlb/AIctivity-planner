<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230524132726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_qcm DROP CONSTRAINT fk_a598607681c06096');
        $this->addSql('ALTER TABLE activity_qcm DROP CONSTRAINT fk_a5986076ff6241a6');
        $this->addSql('DROP TABLE activity_qcm');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE activity_qcm (activity_id INT NOT NULL, qcm_id INT NOT NULL, PRIMARY KEY(activity_id, qcm_id))');
        $this->addSql('CREATE INDEX idx_a5986076ff6241a6 ON activity_qcm (qcm_id)');
        $this->addSql('CREATE INDEX idx_a598607681c06096 ON activity_qcm (activity_id)');
        $this->addSql('ALTER TABLE activity_qcm ADD CONSTRAINT fk_a598607681c06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activity_qcm ADD CONSTRAINT fk_a5986076ff6241a6 FOREIGN KEY (qcm_id) REFERENCES qcm (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
