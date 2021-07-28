<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210728063715 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_deliverable (id INT AUTO_INCREMENT NOT NULL, payment_currency_id INT NOT NULL, created_by_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, delivery_date DATE NOT NULL, payable_amount DOUBLE PRECISION NOT NULL, percentage DOUBLE PRECISION DEFAULT NULL, planned_delivery_date DATE NOT NULL, verify_deliverable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_69B253BACEA851AC (payment_currency_id), INDEX IDX_69B253BAB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_deliverable ADD CONSTRAINT FK_69B253BACEA851AC FOREIGN KEY (payment_currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE project_deliverable ADD CONSTRAINT FK_69B253BAB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE project_deliverable');
    }
}
