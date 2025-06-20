<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250620214620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE record DROP FOREIGN KEY FK_9B349F91C5FF1223');
        $this->addSql('DROP TABLE monster');
        $this->addSql('DROP INDEX IDX_9B349F91C5FF1223 ON record');
        $this->addSql('ALTER TABLE record DROP monster_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monster (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, img_url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, level INT NOT NULL, ankama_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE record ADD monster_id INT NOT NULL');
        $this->addSql('ALTER TABLE record ADD CONSTRAINT FK_9B349F91C5FF1223 FOREIGN KEY (monster_id) REFERENCES monster (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9B349F91C5FF1223 ON record (monster_id)');
    }
}
