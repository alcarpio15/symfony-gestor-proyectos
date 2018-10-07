<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180914152149 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE requerimientos_solicitudes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE requerimientos_solicitudes (id INT NOT NULL, requerimientos_servicio_id INT DEFAULT NULL, requerimientos_area_id INT DEFAULT NULL, procedencia_departamento VARCHAR(255) DEFAULT NULL, procedencia_telefono VARCHAR(255) DEFAULT NULL, procedencia_email VARCHAR(255) DEFAULT NULL, requerimientos_estado INT NOT NULL, requerimientos_creado TIMESTAMP(0) WITH TIME ZONE NOT NULL, requerimientos_modificado TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CDFBB4F9E51C550 ON requerimientos_solicitudes (procedencia_email)');
        $this->addSql('CREATE INDEX IDX_CDFBB4F7F03C9B8 ON requerimientos_solicitudes (requerimientos_servicio_id)');
        $this->addSql('CREATE INDEX IDX_CDFBB4F7D6F3A48 ON requerimientos_solicitudes (requerimientos_area_id)');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD CONSTRAINT FK_CDFBB4F7F03C9B8 FOREIGN KEY (requerimientos_servicio_id) REFERENCES servicios_solicitudes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD CONSTRAINT FK_CDFBB4F7D6F3A48 FOREIGN KEY (requerimientos_area_id) REFERENCES area_coordinacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE requerimientos_solicitudes_id_seq CASCADE');
        $this->addSql('DROP TABLE requerimientos_solicitudes');
    }
}
