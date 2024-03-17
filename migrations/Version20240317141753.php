<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240317141753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE guild (id INT AUTO_INCREMENT NOT NULL, server_id INT NOT NULL, name VARCHAR(30) NOT NULL, description VARCHAR(255) NOT NULL, discord_url VARCHAR(255) NOT NULL, website_url VARCHAR(255) NOT NULL, blason_picture VARCHAR(255) DEFAULT NULL, cover_picture VARCHAR(255) DEFAULT NULL, is_creator TINYINT(1) NOT NULL, INDEX IDX_75407DAB1844E6B7 (server_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE guild ADD CONSTRAINT FK_75407DAB1844E6B7 FOREIGN KEY (server_id) REFERENCES server (id)');
        $this->addSql('ALTER TABLE user ADD guild_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6495F2131EF FOREIGN KEY (guild_id) REFERENCES guild (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6495F2131EF ON user (guild_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6495F2131EF');
        $this->addSql('ALTER TABLE guild DROP FOREIGN KEY FK_75407DAB1844E6B7');
        $this->addSql('DROP TABLE guild');
        $this->addSql('DROP INDEX IDX_8D93D6495F2131EF ON user');
        $this->addSql('ALTER TABLE user DROP guild_id');
    }
}
