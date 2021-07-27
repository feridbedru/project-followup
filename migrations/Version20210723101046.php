<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210723101046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE permission (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, status INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D649B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_user_group (user_id INT NOT NULL, user_group_id INT NOT NULL, INDEX IDX_28657971A76ED395 (user_id), INDEX IDX_286579711ED93D47 (user_group_id), PRIMARY KEY(user_id, user_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group (id INT AUTO_INCREMENT NOT NULL, registered_by_id INT NOT NULL, updated_by_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_8F02BF9D27E92E18 (registered_by_id), INDEX IDX_8F02BF9D896DBBDE (updated_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_group_permission (user_group_id INT NOT NULL, permission_id INT NOT NULL, INDEX IDX_4A91B1C51ED93D47 (user_group_id), INDEX IDX_4A91B1C5FED90CCA (permission_id), PRIMARY KEY(user_group_id, permission_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_user_group ADD CONSTRAINT FK_28657971A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_user_group ADD CONSTRAINT FK_286579711ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D27E92E18 FOREIGN KEY (registered_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D896DBBDE FOREIGN KEY (updated_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_group_permission ADD CONSTRAINT FK_4A91B1C51ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_group_permission ADD CONSTRAINT FK_4A91B1C5FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_group_permission DROP FOREIGN KEY FK_4A91B1C5FED90CCA');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649B03A8386');
        $this->addSql('ALTER TABLE user_user_group DROP FOREIGN KEY FK_28657971A76ED395');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9D27E92E18');
        $this->addSql('ALTER TABLE user_group DROP FOREIGN KEY FK_8F02BF9D896DBBDE');
        $this->addSql('ALTER TABLE user_user_group DROP FOREIGN KEY FK_286579711ED93D47');
        $this->addSql('ALTER TABLE user_group_permission DROP FOREIGN KEY FK_4A91B1C51ED93D47');
        $this->addSql('DROP TABLE permission');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_user_group');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE user_group_permission');
    }
}
