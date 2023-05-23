<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230523120419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE qcm_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE qcm_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE qcm (id INT NOT NULL, question VARCHAR(1000) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE qcm_answer (id INT NOT NULL, question_id INT NOT NULL, qcm_id INT NOT NULL, answer VARCHAR(1000) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A83FC031E27F6BF ON qcm_answer (question_id)');
        $this->addSql('CREATE INDEX IDX_6A83FC03FF6241A6 ON qcm_answer (qcm_id)');
        $this->addSql('ALTER TABLE qcm_answer ADD CONSTRAINT FK_6A83FC031E27F6BF FOREIGN KEY (question_id) REFERENCES qcm (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE qcm_answer ADD CONSTRAINT FK_6A83FC03FF6241A6 FOREIGN KEY (qcm_id) REFERENCES qcm (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE qcm_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE qcm_answer_id_seq CASCADE');
        $this->addSql('ALTER TABLE qcm_answer DROP CONSTRAINT FK_6A83FC031E27F6BF');
        $this->addSql('ALTER TABLE qcm_answer DROP CONSTRAINT FK_6A83FC03FF6241A6');
        $this->addSql('DROP TABLE qcm');
        $this->addSql('DROP TABLE qcm_answer');
    }
}
