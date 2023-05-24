<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230523142522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE activity_question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE activity_question (id INT NOT NULL, activity_id INT NOT NULL, question_id INT NOT NULL, answer_id INT NOT NULL, owner_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_16FDF94081C06096 ON activity_question (activity_id)');
        $this->addSql('CREATE INDEX IDX_16FDF9401E27F6BF ON activity_question (question_id)');
        $this->addSql('CREATE INDEX IDX_16FDF940AA334807 ON activity_question (answer_id)');
        $this->addSql('CREATE INDEX IDX_16FDF9407E3C61F9 ON activity_question (owner_id)');
        $this->addSql('ALTER TABLE activity_question ADD CONSTRAINT FK_16FDF94081C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activity_question ADD CONSTRAINT FK_16FDF9401E27F6BF FOREIGN KEY (question_id) REFERENCES qcm (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activity_question ADD CONSTRAINT FK_16FDF940AA334807 FOREIGN KEY (answer_id) REFERENCES qcm_answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE activity_question ADD CONSTRAINT FK_16FDF9407E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE activity_question_id_seq CASCADE');
        $this->addSql('ALTER TABLE activity_question DROP CONSTRAINT FK_16FDF94081C06096');
        $this->addSql('ALTER TABLE activity_question DROP CONSTRAINT FK_16FDF9401E27F6BF');
        $this->addSql('ALTER TABLE activity_question DROP CONSTRAINT FK_16FDF940AA334807');
        $this->addSql('ALTER TABLE activity_question DROP CONSTRAINT FK_16FDF9407E3C61F9');
        $this->addSql('DROP TABLE activity_question');
    }
}
