<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210726081832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE currency (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(15) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE program (id INT AUTO_INCREMENT NOT NULL, currency_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, stakeholders LONGTEXT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, objective LONGTEXT DEFAULT NULL, status INT NOT NULL, INDEX IDX_92ED778438248176 (currency_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, project_manager_id INT NOT NULL, currency_id INT NOT NULL, program_id INT DEFAULT NULL, created_by_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, stakeholders LONGTEXT NOT NULL, outcome LONGTEXT DEFAULT NULL, status INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_2FB3D0EE12469DE2 (category_id), INDEX IDX_2FB3D0EE60984F51 (project_manager_id), INDEX IDX_2FB3D0EE38248176 (currency_id), INDEX IDX_2FB3D0EE3EB8070A (program_id), INDEX IDX_2FB3D0EEB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project_role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sponsor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, contact_person VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, phone VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED778438248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE12469DE2 FOREIGN KEY (category_id) REFERENCES project_category (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE60984F51 FOREIGN KEY (project_manager_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE38248176 FOREIGN KEY (currency_id) REFERENCES currency (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EE3EB8070A FOREIGN KEY (program_id) REFERENCES program (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE log CHANGE user_id user_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED778438248176');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE38248176');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE3EB8070A');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EE12469DE2');
        $this->addSql('DROP TABLE currency');
        $this->addSql('DROP TABLE program');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_category');
        $this->addSql('DROP TABLE project_role');
        $this->addSql('DROP TABLE resource_type');
        $this->addSql('DROP TABLE sponsor');
        $this->addSql('ALTER TABLE log CHANGE user_id user_id INT DEFAULT NULL');
    }
}
