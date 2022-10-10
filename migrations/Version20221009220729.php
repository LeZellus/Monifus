<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221009220729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monitor (id INT AUTO_INCREMENT NOT NULL, resource_id INT NOT NULL, stock_id INT NOT NULL, price INT NOT NULL, INDEX IDX_E115998589329D25 (resource_id), INDEX IDX_E1159985DCD6110 (stock_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE monitor ADD CONSTRAINT FK_E115998589329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('ALTER TABLE monitor ADD CONSTRAINT FK_E1159985DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monitor DROP FOREIGN KEY FK_E115998589329D25');
        $this->addSql('ALTER TABLE monitor DROP FOREIGN KEY FK_E1159985DCD6110');
        $this->addSql('DROP TABLE monitor');
    }
}
