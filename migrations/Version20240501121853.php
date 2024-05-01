<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501121853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sale ADD stock_id INT NOT NULL');

        // Assure-toi que l'ID que tu utilises ici correspond à la valeur '1' dans la table stock
        $this->addSql('UPDATE sale SET stock_id = (SELECT id FROM stock WHERE quantity = 1 LIMIT 1)');

        $this->addSql('ALTER TABLE sale ADD CONSTRAINT FK_SALE_STOCK FOREIGN KEY (stock_id) REFERENCES stock (id)');
        $this->addSql('CREATE INDEX IDX_SALE_STOCK ON sale (stock_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sale DROP FOREIGN KEY FK_E54BC005DCD6110');
        $this->addSql('DROP INDEX IDX_E54BC005DCD6110 ON sale');
        $this->addSql('ALTER TABLE sale DROP stock_id');
    }
}
