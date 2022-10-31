<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221028141112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business ADD industry_sector_id INT DEFAULT NULL, DROP category');
        $this->addSql('ALTER TABLE business ADD CONSTRAINT FK_8D36E384367E7D2 FOREIGN KEY (industry_sector_id) REFERENCES industry_sector (id)');
        $this->addSql('CREATE INDEX IDX_8D36E384367E7D2 ON business (industry_sector_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business DROP FOREIGN KEY FK_8D36E384367E7D2');
        $this->addSql('DROP INDEX IDX_8D36E384367E7D2 ON business');
        $this->addSql('ALTER TABLE business ADD category VARCHAR(255) DEFAULT NULL, DROP industry_sector_id');
    }
}
