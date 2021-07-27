<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210727075217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE project_members (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, user_id INT NOT NULL, role_id INT NOT NULL, created_by_id INT NOT NULL, status INT NOT NULL, is_working_on_task TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_D3BEDE9A296CD8AE (team_id), INDEX IDX_D3BEDE9AA76ED395 (user_id), INDEX IDX_D3BEDE9AD60322AC (role_id), INDEX IDX_D3BEDE9AB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE project_members ADD CONSTRAINT FK_D3BEDE9A296CD8AE FOREIGN KEY (team_id) REFERENCES project_team (id)');
        $this->addSql('ALTER TABLE project_members ADD CONSTRAINT FK_D3BEDE9AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project_members ADD CONSTRAINT FK_D3BEDE9AD60322AC FOREIGN KEY (role_id) REFERENCES project_role (id)');
        $this->addSql('ALTER TABLE project_members ADD CONSTRAINT FK_D3BEDE9AB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE project_members');
    }
}
