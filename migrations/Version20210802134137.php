<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210802134137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_chat ADD topic_id INT NOT NULL');
        $this->addSql('ALTER TABLE activity_chat ADD CONSTRAINT FK_665929381F55203D FOREIGN KEY (topic_id) REFERENCES project_collaboration_topic (id)');
        $this->addSql('CREATE INDEX IDX_665929381F55203D ON activity_chat (topic_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE activity_chat DROP FOREIGN KEY FK_665929381F55203D');
        $this->addSql('DROP INDEX IDX_665929381F55203D ON activity_chat');
        $this->addSql('ALTER TABLE activity_chat DROP topic_id');
    }
}
