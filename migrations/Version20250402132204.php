<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402132204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email_to_notify (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8A27E7EE7927C74 ON email_to_notify (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__service AS SELECT id, name, thumbnail FROM service');
        $this->addSql('DROP TABLE service');
        $this->addSql('CREATE TABLE service (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO service (id, name, thumbnail) SELECT id, name, thumbnail FROM __temp__service');
        $this->addSql('DROP TABLE __temp__service');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE email_to_notify');
        $this->addSql('CREATE TEMPORARY TABLE __temp__service AS SELECT id, name, thumbnail FROM service');
        $this->addSql('DROP TABLE service');
        $this->addSql('CREATE TABLE service (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO service (id, name, thumbnail) SELECT id, name, thumbnail FROM __temp__service');
        $this->addSql('DROP TABLE __temp__service');
    }
}
