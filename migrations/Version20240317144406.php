<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240317144406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guild ADD leader_id INT NOT NULL, DROP is_creator');
        $this->addSql('ALTER TABLE guild ADD CONSTRAINT FK_75407DAB73154ED4 FOREIGN KEY (leader_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_75407DAB73154ED4 ON guild (leader_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guild DROP FOREIGN KEY FK_75407DAB73154ED4');
        $this->addSql('DROP INDEX UNIQ_75407DAB73154ED4 ON guild');
        $this->addSql('ALTER TABLE guild ADD is_creator TINYINT(1) NOT NULL, DROP leader_id');
    }
}
