<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210728125545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_activity (id INT AUTO_INCREMENT NOT NULL, milestone_id INT NOT NULL, created_by_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, status INT NOT NULL, due_date DATE NOT NULL, display_order INT NOT NULL, is_active TINYINT(1) NOT NULL, can_be_concurrent TINYINT(1) NOT NULL, weight DOUBLE PRECISION DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_913A82814B3E2EDA (milestone_id), INDEX IDX_913A8281B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_milestone_status (id INT AUTO_INCREMENT NOT NULL, milestone_id INT NOT NULL, created_by_id INT NOT NULL, comment LONGTEXT NOT NULL, status INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_CEBBE6A74B3E2EDA (milestone_id), INDEX IDX_CEBBE6A7B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_activity ADD CONSTRAINT FK_913A82814B3E2EDA FOREIGN KEY (milestone_id) REFERENCES project_milestone (id)');
        $this->addSql('ALTER TABLE project_activity ADD CONSTRAINT FK_913A8281B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project_milestone_status ADD CONSTRAINT FK_CEBBE6A74B3E2EDA FOREIGN KEY (milestone_id) REFERENCES project_milestone (id)');
        $this->addSql('ALTER TABLE project_milestone_status ADD CONSTRAINT FK_CEBBE6A7B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE project_activity');
        $this->addSql('DROP TABLE project_milestone_status');
    }
}
