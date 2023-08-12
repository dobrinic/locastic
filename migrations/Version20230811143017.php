<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230811143017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE races (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', average_time_medium TIME DEFAULT NULL COMMENT \'(DC2Type:time_immutable)\', average_time_long TIME DEFAULT NULL COMMENT \'(DC2Type:time_immutable)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE runners (id INT AUTO_INCREMENT NOT NULL, race_id INT NOT NULL, name VARCHAR(50) NOT NULL, age_category VARCHAR(6) NOT NULL, distance VARCHAR(10) NOT NULL, finish_time TIME NOT NULL COMMENT \'(DC2Type:time_immutable)\', placement SMALLINT DEFAULT NULL, age_placement SMALLINT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_DA96F92B6E59D40D (race_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE runners ADD CONSTRAINT FK_DA96F92B6E59D40D FOREIGN KEY (race_id) REFERENCES races (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE runners DROP FOREIGN KEY FK_DA96F92B6E59D40D');
        $this->addSql('DROP TABLE races');
        $this->addSql('DROP TABLE runners');
    }
}
