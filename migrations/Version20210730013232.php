<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210730013232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_progress (id INT AUTO_INCREMENT NOT NULL, activity_id INT NOT NULL, created_by_id INT NOT NULL, content LONGTEXT NOT NULL, file VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_820B424881C06096 (activity_id), INDEX IDX_820B4248B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_progress ADD CONSTRAINT FK_820B424881C06096 FOREIGN KEY (activity_id) REFERENCES project_activity (id)');
        $this->addSql('ALTER TABLE activity_progress ADD CONSTRAINT FK_820B4248B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activity_progress');
    }
}
