<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210730014036 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE activity_verification (id INT AUTO_INCREMENT NOT NULL, activity_user_id INT NOT NULL, created_by_id INT NOT NULL, comment LONGTEXT NOT NULL, status INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_5349074AA73CA575 (activity_user_id), INDEX IDX_5349074AB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE activity_verification ADD CONSTRAINT FK_5349074AA73CA575 FOREIGN KEY (activity_user_id) REFERENCES activity_user (id)');
        $this->addSql('ALTER TABLE activity_verification ADD CONSTRAINT FK_5349074AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE activity_verification');
    }
}
