<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160822184000 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD user_organisation_id INT DEFAULT NULL, ADD primary_building_id INT DEFAULT NULL, ADD name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6498CB7790A FOREIGN KEY (user_organisation_id) REFERENCES organisation (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E9C51E93 FOREIGN KEY (primary_building_id) REFERENCES building (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6498CB7790A ON user (user_organisation_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E9C51E93 ON user (primary_building_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6498CB7790A');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E9C51E93');
        $this->addSql('DROP INDEX IDX_8D93D6498CB7790A ON user');
        $this->addSql('DROP INDEX IDX_8D93D649E9C51E93 ON user');
        $this->addSql('ALTER TABLE user DROP user_organisation_id, DROP primary_building_id, DROP name');
    }
}
