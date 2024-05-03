<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240503095909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sale RENAME INDEX idx_sale_stock TO IDX_E54BC005DCD6110');
        $this->addSql('ALTER TABLE user CHANGE discord_id discord_id BIGINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE discord_id discord_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sale RENAME INDEX idx_e54bc005dcd6110 TO IDX_SALE_STOCK');
    }
}
