<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190209032704 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE historial_tarea_entradas ADD historial_sujeto_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE historial_tarea_entradas ADD CONSTRAINT FK_5E4663032157CA1 FOREIGN KEY (historial_sujeto_id) REFERENCES gestor_usuarios (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5E4663032157CA1 ON historial_tarea_entradas (historial_sujeto_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE historial_tarea_entradas DROP CONSTRAINT FK_5E4663032157CA1');
        $this->addSql('DROP INDEX IDX_5E4663032157CA1');
        $this->addSql('ALTER TABLE historial_tarea_entradas DROP historial_sujeto_id');
    }
}
