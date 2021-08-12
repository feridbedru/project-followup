<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210811063436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_structure (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, reports_to_id INT DEFAULT NULL, created_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_7E3D723D166D1F9C (project_id), INDEX IDX_7E3D723D9BE3208E (reports_to_id), INDEX IDX_7E3D723DB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_structure ADD CONSTRAINT FK_7E3D723D166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_structure ADD CONSTRAINT FK_7E3D723D9BE3208E FOREIGN KEY (reports_to_id) REFERENCES project_structure (id)');
        $this->addSql('ALTER TABLE project_structure ADD CONSTRAINT FK_7E3D723DB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_structure DROP FOREIGN KEY FK_7E3D723D9BE3208E');
        $this->addSql('DROP TABLE project_structure');
    }
}
