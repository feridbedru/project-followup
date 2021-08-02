<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210802120846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_activity ADD project_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_activity ADD CONSTRAINT FK_913A8281166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('CREATE INDEX IDX_913A8281166D1F9C ON project_activity (project_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_activity DROP FOREIGN KEY FK_913A8281166D1F9C');
        $this->addSql('DROP INDEX IDX_913A8281166D1F9C ON project_activity');
        $this->addSql('ALTER TABLE project_activity DROP project_id');
    }
}
