<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240124210718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE record ADD monster_id INT NOT NULL, ADD time TIME NOT NULL, ADD video_link VARCHAR(255) NOT NULL, ADD is_approved TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE record ADD CONSTRAINT FK_9B349F91C5FF1223 FOREIGN KEY (monster_id) REFERENCES monster (id)');
        $this->addSql('CREATE INDEX IDX_9B349F91C5FF1223 ON record (monster_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE record DROP FOREIGN KEY FK_9B349F91C5FF1223');
        $this->addSql('DROP INDEX IDX_9B349F91C5FF1223 ON record');
        $this->addSql('ALTER TABLE record DROP monster_id, DROP time, DROP video_link, DROP is_approved');
    }
}
