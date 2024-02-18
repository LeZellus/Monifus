<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218010019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price ADD user_id INT NOT NULL, ADD resource_id INT NOT NULL');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE price ADD CONSTRAINT FK_CAC822D989329D25 FOREIGN KEY (resource_id) REFERENCES resource (id)');
        $this->addSql('CREATE INDEX IDX_CAC822D9A76ED395 ON price (user_id)');
        $this->addSql('CREATE INDEX IDX_CAC822D989329D25 ON price (resource_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D9A76ED395');
        $this->addSql('ALTER TABLE price DROP FOREIGN KEY FK_CAC822D989329D25');
        $this->addSql('DROP INDEX IDX_CAC822D9A76ED395 ON price');
        $this->addSql('DROP INDEX IDX_CAC822D989329D25 ON price');
        $this->addSql('ALTER TABLE price DROP user_id, DROP resource_id');
    }
}
