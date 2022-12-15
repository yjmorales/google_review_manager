<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221213053227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE place ADD business_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CDA89DB457 FOREIGN KEY (business_id) REFERENCES business (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_741D53CDA89DB457 ON place (business_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CDA89DB457');
        $this->addSql('DROP INDEX UNIQ_741D53CDA89DB457 ON place');
        $this->addSql('ALTER TABLE place DROP business_id');
    }
}
