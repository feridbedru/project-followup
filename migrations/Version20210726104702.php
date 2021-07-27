<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210726104702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_version (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, created_by_id INT NOT NULL, version VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, released_date DATE NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_2902DFA6166D1F9C (project_id), INDEX IDX_2902DFA6B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_version ADD CONSTRAINT FK_2902DFA6166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE project_version ADD CONSTRAINT FK_2902DFA6B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE project_version');
    }
}
