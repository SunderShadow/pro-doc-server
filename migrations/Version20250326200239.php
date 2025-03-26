<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326200239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advice_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, thumbnail_extension VARCHAR(255) DEFAULT NULL, excerpt CLOB NOT NULL, body CLOB NOT NULL, created_at TIME DEFAULT \'now()\' NOT NULL --(DC2Type:time_immutable)
        )');
        $this->addSql('CREATE TABLE advice_post_advice_post_tag (advice_post_id INTEGER NOT NULL, advice_post_tag_id INTEGER NOT NULL, PRIMARY KEY(advice_post_id, advice_post_tag_id), CONSTRAINT FK_BD9EB10980A6D59 FOREIGN KEY (advice_post_id) REFERENCES advice_post (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_BD9EB109B2C7916 FOREIGN KEY (advice_post_tag_id) REFERENCES advice_post_tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_BD9EB10980A6D59 ON advice_post_advice_post_tag (advice_post_id)');
        $this->addSql('CREATE INDEX IDX_BD9EB109B2C7916 ON advice_post_advice_post_tag (advice_post_tag_id)');
        $this->addSql('CREATE TABLE advice_post_tag (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TABLE advice_post_tag_advice_post_tag (advice_post_tag_source INTEGER NOT NULL, advice_post_tag_target INTEGER NOT NULL, PRIMARY KEY(advice_post_tag_source, advice_post_tag_target), CONSTRAINT FK_1B24879AFCFBF42D FOREIGN KEY (advice_post_tag_source) REFERENCES advice_post_tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1B24879AE51EA4A2 FOREIGN KEY (advice_post_tag_target) REFERENCES advice_post_tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1B24879AFCFBF42D ON advice_post_tag_advice_post_tag (advice_post_tag_source)');
        $this->addSql('CREATE INDEX IDX_1B24879AE51EA4A2 ON advice_post_tag_advice_post_tag (advice_post_tag_target)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE advice_post');
        $this->addSql('DROP TABLE advice_post_advice_post_tag');
        $this->addSql('DROP TABLE advice_post_tag');
        $this->addSql('DROP TABLE advice_post_tag_advice_post_tag');
    }
}
