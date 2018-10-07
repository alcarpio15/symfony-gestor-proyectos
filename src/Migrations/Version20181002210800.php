<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20181002210800 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE gestor_usuarios ADD requerimientos_area_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gestor_usuarios ADD CONSTRAINT FK_D14FE3FC7D6F3A48 FOREIGN KEY (requerimientos_area_id) REFERENCES area_coordinacion (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D14FE3FC7D6F3A48 ON gestor_usuarios (requerimientos_area_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE gestor_usuarios DROP CONSTRAINT FK_D14FE3FC7D6F3A48');
        $this->addSql('DROP INDEX IDX_D14FE3FC7D6F3A48');
        $this->addSql('ALTER TABLE gestor_usuarios DROP requerimientos_area_id');
    }
}
