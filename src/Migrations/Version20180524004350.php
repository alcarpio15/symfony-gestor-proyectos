<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180524004350 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE servicios_solicitudes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE gestor_usuarios_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE servicios_solicitudes (id INT NOT NULL, servicio_titulo TEXT NOT NULL, servicio_descrp TEXT NOT NULL, servicio_estado INT NOT NULL, servicio_creado TIMESTAMP(0) WITH TIME ZONE NOT NULL, servicio_modificado TIMESTAMP(0) WITH TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE gestor_usuarios (id INT NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(64) NOT NULL, roles TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D14FE3FCE7927C74 ON gestor_usuarios (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D14FE3FCF85E0677 ON gestor_usuarios (username)');
        $this->addSql('COMMENT ON COLUMN gestor_usuarios.roles IS \'(DC2Type:array)\'');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE servicios_solicitudes_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE gestor_usuarios_id_seq CASCADE');
        $this->addSql('DROP TABLE servicios_solicitudes');
        $this->addSql('DROP TABLE gestor_usuarios');
    }
}
