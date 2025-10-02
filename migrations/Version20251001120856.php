<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251001120856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE criteria_groups (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, description LONGTEXT DEFAULT NULL, code VARCHAR(50) NOT NULL, display_mode VARCHAR(20) NOT NULL, sort_order INT NOT NULL, is_active TINYINT(1) NOT NULL, is_multiple TINYINT(1) NOT NULL, max_selections INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE search_criterias (id INT AUTO_INCREMENT NOT NULL, criteria_group_id INT NOT NULL, name VARCHAR(100) NOT NULL, field_name VARCHAR(100) NOT NULL, type VARCHAR(50) NOT NULL, operators JSON DEFAULT NULL, options JSON DEFAULT NULL, sort_order INT NOT NULL, is_active TINYINT(1) NOT NULL, is_required TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_3A1C738E8E553CC3 (criteria_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE search_criterias ADD CONSTRAINT FK_3A1C738E8E553CC3 FOREIGN KEY (criteria_group_id) REFERENCES criteria_groups (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE search_criterias DROP FOREIGN KEY FK_3A1C738E8E553CC3');
        $this->addSql('DROP TABLE criteria_groups');
        $this->addSql('DROP TABLE search_criterias');
    }
}
