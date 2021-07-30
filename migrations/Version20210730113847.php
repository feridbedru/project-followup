<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210730113847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project_members DROP FOREIGN KEY FK_D3BEDE9A296CD8AE');
        $this->addSql('DROP TABLE project_team');
        $this->addSql('ALTER TABLE project_deliverable DROP FOREIGN KEY FK_69B253BACEA851AC');
        $this->addSql('DROP INDEX IDX_69B253BACEA851AC ON project_deliverable');
        $this->addSql('ALTER TABLE project_deliverable DROP payment_currency_id, DROP payable_amount');
        $this->addSql('DROP INDEX IDX_D3BEDE9A296CD8AE ON project_members');
        $this->addSql('ALTER TABLE project_members DROP team_id');
        $this->addSql('ALTER TABLE project_milestone ADD planned_delivery_date DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_team (id INT AUTO_INCREMENT NOT NULL, project_id INT NOT NULL, created_by_id INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, INDEX IDX_FD716E07166D1F9C (project_id), INDEX IDX_FD716E07B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE project_team ADD CONSTRAINT FK_FD716E07166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE project_team ADD CONSTRAINT FK_FD716E07B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE project_deliverable ADD payment_currency_id INT NOT NULL, ADD payable_amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE project_deliverable ADD CONSTRAINT FK_69B253BACEA851AC FOREIGN KEY (payment_currency_id) REFERENCES currency (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_69B253BACEA851AC ON project_deliverable (payment_currency_id)');
        $this->addSql('ALTER TABLE project_members ADD team_id INT NOT NULL');
        $this->addSql('ALTER TABLE project_members ADD CONSTRAINT FK_D3BEDE9A296CD8AE FOREIGN KEY (team_id) REFERENCES project_team (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_D3BEDE9A296CD8AE ON project_members (team_id)');
        $this->addSql('ALTER TABLE project_milestone DROP planned_delivery_date');
    }
}
