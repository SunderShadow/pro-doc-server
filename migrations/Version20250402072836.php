<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250402072836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advice_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, thumbnail_extension VARCHAR(255) DEFAULT NULL, is_published BOOLEAN NOT NULL, published_at TIME DEFAULT NULL --(DC2Type:time_immutable)
        , excerpt CLOB NOT NULL, body CLOB NOT NULL, created_at TIME DEFAULT \'now()\' NOT NULL --(DC2Type:time_immutable)
        )');
        $this->addSql('CREATE TABLE advice_post_tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE advice_post_tag_advice_post (advice_post_tag_id INTEGER NOT NULL, advice_post_id INTEGER NOT NULL, PRIMARY KEY(advice_post_tag_id, advice_post_id), CONSTRAINT FK_2044D3169B2C7916 FOREIGN KEY (advice_post_tag_id) REFERENCES advice_post_tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_2044D316980A6D59 FOREIGN KEY (advice_post_id) REFERENCES advice_post (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_2044D3169B2C7916 ON advice_post_tag_advice_post (advice_post_tag_id)');
        $this->addSql('CREATE INDEX IDX_2044D316980A6D59 ON advice_post_tag_advice_post (advice_post_id)');
        $this->addSql('CREATE TABLE page_config (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, page_name VARCHAR(255) NOT NULL, config CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE TABLE service (id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE advice_post');
        $this->addSql('DROP TABLE advice_post_tag');
        $this->addSql('DROP TABLE advice_post_tag_advice_post');
        $this->addSql('DROP TABLE page_config');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE user');
    }
}
