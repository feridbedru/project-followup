<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210730101906 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE program ADD program_manager_id INT NOT NULL');
        $this->addSql('ALTER TABLE program ADD CONSTRAINT FK_92ED77846E04C9D7 FOREIGN KEY (program_manager_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_92ED77846E04C9D7 ON program (program_manager_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE program DROP FOREIGN KEY FK_92ED77846E04C9D7');
        $this->addSql('DROP INDEX IDX_92ED77846E04C9D7 ON program');
        $this->addSql('ALTER TABLE program DROP program_manager_id');
    }
}
