<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240119002914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monitor DROP FOREIGN KEY FK_E1159985DCD6110');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP INDEX IDX_E1159985DCD6110 ON monitor');
        $this->addSql('ALTER TABLE monitor DROP stock_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE monitor ADD stock_id INT NOT NULL');
        $this->addSql('ALTER TABLE monitor ADD CONSTRAINT FK_E1159985DCD6110 FOREIGN KEY (stock_id) REFERENCES stock (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E1159985DCD6110 ON monitor (stock_id)');
    }
}
