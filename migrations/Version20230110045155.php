<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230110045155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE place CHANGE city city VARCHAR(100) DEFAULT NULL, CHANGE state state VARCHAR(100) DEFAULT NULL, CHANGE zip_code zip_code VARCHAR(15) DEFAULT NULL, CHANGE country country VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE place CHANGE city city VARCHAR(100) NOT NULL, CHANGE state state VARCHAR(100) NOT NULL, CHANGE zip_code zip_code VARCHAR(15) NOT NULL, CHANGE country country VARCHAR(100) NOT NULL');
    }
}
