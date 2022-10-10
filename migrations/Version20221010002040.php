<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221010002040 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monitor ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE monitor ADD CONSTRAINT FK_E1159985A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E1159985A76ED395 ON monitor (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monitor DROP FOREIGN KEY FK_E1159985A76ED395');
        $this->addSql('DROP INDEX IDX_E1159985A76ED395 ON monitor');
        $this->addSql('ALTER TABLE monitor DROP user_id');
    }
}
