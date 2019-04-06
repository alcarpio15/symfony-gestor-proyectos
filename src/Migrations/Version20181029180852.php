<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181029180852 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE tarea_solicitudes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE tarea_solicitudes (id INT NOT NULL, tarea_requerimiento_id INT DEFAULT NULL, tarea_desarrollador_id INT DEFAULT NULL, tarea_titulo TEXT NOT NULL, tarea_descrp TEXT DEFAULT NULL, tarea_entrega_estimada TIMESTAMP(0) WITH TIME ZONE NOT NULL, tarea_estado INT NOT NULL, tarea_creado TIMESTAMP(0) WITH TIME ZONE NOT NULL, tarea_modificado TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1BC528348A3DFB98 ON tarea_solicitudes (tarea_requerimiento_id)');
        $this->addSql('CREATE INDEX IDX_1BC5283460C9E4B4 ON tarea_solicitudes (tarea_desarrollador_id)');
        $this->addSql('ALTER TABLE tarea_solicitudes ADD CONSTRAINT FK_1BC528348A3DFB98 FOREIGN KEY (tarea_requerimiento_id) REFERENCES requerimientos_solicitudes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tarea_solicitudes ADD CONSTRAINT FK_1BC5283460C9E4B4 FOREIGN KEY (tarea_desarrollador_id) REFERENCES gestor_usuarios (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD requerimientos_procedencia_departamento VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD requerimientos_procedencia_telefono VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD requerimientos_procedencia_email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP procedencia_departamento');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP procedencia_telefono');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP procedencia_email');
        $this->addSql('ALTER TABLE requerimientos_solicitudes RENAME COLUMN servicio_titulo TO requerimientos_titulo');
        $this->addSql('ALTER TABLE requerimientos_solicitudes RENAME COLUMN servicio_descrp TO requerimientos_descrp');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE tarea_solicitudes_id_seq CASCADE');
        $this->addSql('DROP TABLE tarea_solicitudes');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD procedencia_departamento VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD procedencia_telefono VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD procedencia_email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP requerimientos_procedencia_departamento');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP requerimientos_procedencia_telefono');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP requerimientos_procedencia_email');
        $this->addSql('ALTER TABLE requerimientos_solicitudes RENAME COLUMN requerimientos_titulo TO servicio_titulo');
        $this->addSql('ALTER TABLE requerimientos_solicitudes RENAME COLUMN requerimientos_descrp TO servicio_descrp');
    }
}
