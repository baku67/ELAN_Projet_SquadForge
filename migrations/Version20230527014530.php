<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230527014530 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE topic_post_user DROP FOREIGN KEY FK_AD5325FAA76ED395');
        $this->addSql('ALTER TABLE topic_post_user DROP FOREIGN KEY FK_AD5325FAC3225EF9');
        $this->addSql('DROP TABLE topic_post_user');
        $this->addSql('ALTER TABLE game ADD sub_banner VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE topic_post_user (topic_post_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_AD5325FAA76ED395 (user_id), INDEX IDX_AD5325FAC3225EF9 (topic_post_id), PRIMARY KEY(topic_post_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'Ancienne table likes ? (mtn "post_like" avec upvotes/downvotes)\' ');
        $this->addSql('ALTER TABLE topic_post_user ADD CONSTRAINT FK_AD5325FAA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic_post_user ADD CONSTRAINT FK_AD5325FAC3225EF9 FOREIGN KEY (topic_post_id) REFERENCES topic_post (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game DROP sub_banner');
    }
}
