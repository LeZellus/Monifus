<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221010144741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monitor (id INT AUTO_INCREMENT NOT NULL, resource_id INT NOT NULL, stock_id INT NOT NULL, user_id INT NOT NULL, price INT NOT NULL, INDEX IDX_E115998589329D25 (resource_id), INDEX IDX_E1159985DCD6110 (stock_id), INDEX IDX_E1159985A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, ankama_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, level INT NOT NULL, description LONGTEXT DEFAULT NULL, img_url VARCHAR(255) DEFAULT NULL, is_important TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, pseudonyme_website VARCHAR(255) NOT NULL, pseudonyme_dofus VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE monitor ADD CONSTRAINT FK_E115998589329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE monitor ADD CONSTRAINT FK_E1159985DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('ALTER TABLE monitor ADD CONSTRAINT FK_E1159985A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monitor DROP FOREIGN KEY FK_E115998589329D25');
        $this->addSql('ALTER TABLE monitor DROP FOREIGN KEY FK_E1159985DCD6110');
        $this->addSql('ALTER TABLE monitor DROP FOREIGN KEY FK_E1159985A76ED395');
        $this->addSql('DROP TABLE monitor');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE user');
    }
}
