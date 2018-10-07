<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181002220911 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE gestor_usuarios DROP CONSTRAINT fk_d14fe3fc7d6f3a48');
        $this->addSql('DROP INDEX idx_d14fe3fc7d6f3a48');
        $this->addSql('ALTER TABLE gestor_usuarios DROP requerimientos_area_id');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP CONSTRAINT fk_cdfbb4f7f03c9b8');
        $this->addSql('DROP INDEX idx_cdfbb4f7f03c9b8');
        $this->addSql('DROP INDEX uniq_cdfbb4f9e51c550');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP requerimientos_servicio_id');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP procedencia_departamento');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP procedencia_telefono');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP procedencia_email');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE gestor_usuarios ADD requerimientos_area_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gestor_usuarios ADD CONSTRAINT fk_d14fe3fc7d6f3a48 FOREIGN KEY (requerimientos_area_id) REFERENCES area_coordinacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_d14fe3fc7d6f3a48 ON gestor_usuarios (requerimientos_area_id)');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD requerimientos_servicio_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD procedencia_departamento VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD procedencia_telefono VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD procedencia_email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD CONSTRAINT fk_cdfbb4f7f03c9b8 FOREIGN KEY (requerimientos_servicio_id) REFERENCES servicios_solicitudes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_cdfbb4f7f03c9b8 ON requerimientos_solicitudes (requerimientos_servicio_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_cdfbb4f9e51c550 ON requerimientos_solicitudes (procedencia_email)');
    }
}
