<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230523120955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_qcm (activity_id INT NOT NULL, qcm_id INT NOT NULL, PRIMARY KEY(activity_id, qcm_id))');
        $this->addSql('CREATE INDEX IDX_A598607681C06096 ON activity_qcm (activity_id)');
        $this->addSql('CREATE INDEX IDX_A5986076FF6241A6 ON activity_qcm (qcm_id)');
        $this->addSql('ALTER TABLE activity_qcm ADD CONSTRAINT FK_A598607681C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activity_qcm ADD CONSTRAINT FK_A5986076FF6241A6 FOREIGN KEY (qcm_id) REFERENCES qcm (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qcm ADD type VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE activity_qcm DROP CONSTRAINT FK_A598607681C06096');
        $this->addSql('ALTER TABLE activity_qcm DROP CONSTRAINT FK_A5986076FF6241A6');
        $this->addSql('DROP TABLE activity_qcm');
        $this->addSql('ALTER TABLE qcm DROP type');
    }
}
