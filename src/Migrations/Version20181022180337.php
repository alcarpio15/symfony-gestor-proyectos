<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181022180337 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE servicios_solicitudes ALTER servicio_descrp DROP NOT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD servicio_titulo TEXT NOT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes ADD servicio_descrp TEXT DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE servicios_solicitudes ALTER servicio_descrp SET NOT NULL');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP servicio_titulo');
        $this->addSql('ALTER TABLE requerimientos_solicitudes DROP servicio_descrp');
    }
}
