<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210728130025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_files (id INT AUTO_INCREMENT NOT NULL, activity_id INT NOT NULL, uploaded_by_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, file VARCHAR(255) NOT NULL, uploaded_at DATETIME NOT NULL, is_public TINYINT(1) NOT NULL, INDEX IDX_183776EA81C06096 (activity_id), INDEX IDX_183776EAA2B28FE8 (uploaded_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_files ADD CONSTRAINT FK_183776EA81C06096 FOREIGN KEY (activity_id) REFERENCES project_activity (id)');
        $this->addSql('ALTER TABLE activity_files ADD CONSTRAINT FK_183776EAA2B28FE8 FOREIGN KEY (uploaded_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activity_files');
    }
}
