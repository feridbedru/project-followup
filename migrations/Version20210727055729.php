<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210727055729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_resource (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, resource_type_id INT NOT NULL, uploaded_by_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, file VARCHAR(255) NOT NULL, status INT NOT NULL, is_public TINYINT(1) DEFAULT NULL, is_pinned TINYINT(1) DEFAULT NULL, uploaded_at DATETIME NOT NULL, INDEX IDX_81DF7FCD166D1F9C (project_id), INDEX IDX_81DF7FCD98EC6B7B (resource_type_id), INDEX IDX_81DF7FCDA2B28FE8 (uploaded_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_resource ADD CONSTRAINT FK_81DF7FCD166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_resource ADD CONSTRAINT FK_81DF7FCD98EC6B7B FOREIGN KEY (resource_type_id) REFERENCES resource_type (id)');
        $this->addSql('ALTER TABLE project_resource ADD CONSTRAINT FK_81DF7FCDA2B28FE8 FOREIGN KEY (uploaded_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE project_resource');
    }
}
