<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180612014815 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE servicios_solicitudes ADD servicio_autor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE servicios_solicitudes ADD CONSTRAINT FK_11A8A49B6C346B FOREIGN KEY (servicio_autor_id) REFERENCES gestor_usuarios (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_11A8A49B6C346B ON servicios_solicitudes (servicio_autor_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE servicios_solicitudes DROP CONSTRAINT FK_11A8A49B6C346B');
        $this->addSql('DROP INDEX IDX_11A8A49B6C346B');
        $this->addSql('ALTER TABLE servicios_solicitudes DROP servicio_autor_id');
    }
}
