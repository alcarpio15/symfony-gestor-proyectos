<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20180710125451 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE area_coordinacion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE area_coordinacion (id INT NOT NULL, area_coordinador_id INT DEFAULT NULL, area VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B37BE0845543AEE1 ON area_coordinacion (area_coordinador_id)');
        $this->addSql('ALTER TABLE area_coordinacion ADD CONSTRAINT FK_B37BE0845543AEE1 FOREIGN KEY (area_coordinador_id) REFERENCES gestor_usuarios (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE gestor_usuarios ADD nombres VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE gestor_usuarios ADD apellidos VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE area_coordinacion_id_seq CASCADE');
        $this->addSql('DROP TABLE area_coordinacion');
        $this->addSql('ALTER TABLE gestor_usuarios DROP nombres');
        $this->addSql('ALTER TABLE gestor_usuarios DROP apellidos');
    }
}
