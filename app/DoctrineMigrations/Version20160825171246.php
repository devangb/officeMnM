<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160825171246 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE booking (id INT AUTO_INCREMENT NOT NULL, room_id INT NOT NULL, organiser_id INT NOT NULL, start_time DATETIME NOT NULL, end_time DATETIME NOT NULL, extended_time DATETIME NOT NULL, purpose LONGTEXT NOT NULL, capacity INT NOT NULL, INDEX IDX_E00CEDDE54177093 (room_id), INDEX IDX_E00CEDDEA0631C12 (organiser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE building (id INT AUTO_INCREMENT NOT NULL, organisation_id INT NOT NULL, building_name VARCHAR(255) NOT NULL, INDEX IDX_E16F61D49E6B1585 (organisation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organisation (id INT AUTO_INCREMENT NOT NULL, organisation_name VARCHAR(255) NOT NULL, organisation_domain VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE room (id INT AUTO_INCREMENT NOT NULL, building_id INT NOT NULL, room_name VARCHAR(255) NOT NULL, room_floor INT NOT NULL, capacity INT NOT NULL, has_projector TINYINT(1) NOT NULL, has_whiteboard TINYINT(1) NOT NULL, INDEX IDX_729F519B4D2A7E12 (building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_organisation_id INT NOT NULL, primary_building_id INT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6498CB7790A (user_organisation_id), INDEX IDX_8D93D649E9C51E93 (primary_building_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDE54177093 FOREIGN KEY (room_id) REFERENCES room (id)');
        $this->addSql('ALTER TABLE booking ADD CONSTRAINT FK_E00CEDDEA0631C12 FOREIGN KEY (organiser_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE building ADD CONSTRAINT FK_E16F61D49E6B1585 FOREIGN KEY (organisation_id) REFERENCES organisation (id)');
        $this->addSql('ALTER TABLE room ADD CONSTRAINT FK_729F519B4D2A7E12 FOREIGN KEY (building_id) REFERENCES building (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498CB7790A FOREIGN KEY (user_organisation_id) REFERENCES organisation (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E9C51E93 FOREIGN KEY (primary_building_id) REFERENCES building (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE room DROP FOREIGN KEY FK_729F519B4D2A7E12');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E9C51E93');
        $this->addSql('ALTER TABLE building DROP FOREIGN KEY FK_E16F61D49E6B1585');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498CB7790A');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDE54177093');
        $this->addSql('ALTER TABLE booking DROP FOREIGN KEY FK_E00CEDDEA0631C12');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE building');
        $this->addSql('DROP TABLE organisation');
        $this->addSql('DROP TABLE room');
        $this->addSql('DROP TABLE user');
    }
}
