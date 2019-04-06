<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181213034428 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE historial_tarea_entradas_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE historial_tarea_entradas (id INT NOT NULL, historial_tarea_id INT DEFAULT NULL, tarea_descrp TEXT DEFAULT NULL, tarea_estado INT NOT NULL, tarea_creado TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E466303B5554546 ON historial_tarea_entradas (historial_tarea_id)');
        $this->addSql('ALTER TABLE historial_tarea_entradas ADD CONSTRAINT FK_5E466303B5554546 FOREIGN KEY (historial_tarea_id) REFERENCES tarea_solicitudes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE historial_tarea_entradas_id_seq CASCADE');
        $this->addSql('DROP TABLE historial_tarea_entradas');
    }
}
