<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260408005035 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agents (id INT NOT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_9596AB6EE7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE amenities (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_EB7054775E237E06 (name), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE locations (id INT AUTO_INCREMENT NOT NULL, address VARCHAR(255) NOT NULL, city VARCHAR(100) NOT NULL, postal_code VARCHAR(20) NOT NULL, country VARCHAR(100) NOT NULL, latitude NUMERIC(10, 8) DEFAULT NULL, longitude NUMERIC(11, 8) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE properties (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, price NUMERIC(15, 2) NOT NULL, currency VARCHAR(10) NOT NULL, type VARCHAR(50) NOT NULL, status VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, bedrooms INT DEFAULT NULL, bathrooms INT DEFAULT NULL, area NUMERIC(10, 2) DEFAULT NULL, land_area NUMERIC(10, 2) DEFAULT NULL, floor INT DEFAULT NULL, floors INT DEFAULT NULL, total_units INT DEFAULT NULL, year_built INT DEFAULT NULL, garage TINYINT DEFAULT NULL, parking TINYINT DEFAULT NULL, furnished TINYINT DEFAULT NULL, elevator TINYINT DEFAULT NULL, dpe VARCHAR(5) DEFAULT NULL, ges VARCHAR(5) DEFAULT NULL, location_id INT DEFAULT NULL, agent_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_87C331C7989D9B62 (slug), INDEX IDX_87C331C764D218E (location_id), INDEX IDX_87C331C73414710B (agent_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE property_amenities (property_id INT NOT NULL, amenity_id INT NOT NULL, INDEX IDX_9A9F56CA549213EC (property_id), INDEX IDX_9A9F56CA9F9F1305 (amenity_id), PRIMARY KEY (property_id, amenity_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE property_images (id INT AUTO_INCREMENT NOT NULL, image_url VARCHAR(500) NOT NULL, property_id INT NOT NULL, INDEX IDX_9E68D116549213EC (property_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE properties ADD CONSTRAINT FK_87C331C764D218E FOREIGN KEY (location_id) REFERENCES locations (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE properties ADD CONSTRAINT FK_87C331C73414710B FOREIGN KEY (agent_id) REFERENCES agents (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE property_amenities ADD CONSTRAINT FK_9A9F56CA549213EC FOREIGN KEY (property_id) REFERENCES properties (id)');
        $this->addSql('ALTER TABLE property_amenities ADD CONSTRAINT FK_9A9F56CA9F9F1305 FOREIGN KEY (amenity_id) REFERENCES amenities (id)');
        $this->addSql('ALTER TABLE property_images ADD CONSTRAINT FK_9E68D116549213EC FOREIGN KEY (property_id) REFERENCES properties (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE properties DROP FOREIGN KEY FK_87C331C764D218E');
        $this->addSql('ALTER TABLE properties DROP FOREIGN KEY FK_87C331C73414710B');
        $this->addSql('ALTER TABLE property_amenities DROP FOREIGN KEY FK_9A9F56CA549213EC');
        $this->addSql('ALTER TABLE property_amenities DROP FOREIGN KEY FK_9A9F56CA9F9F1305');
        $this->addSql('ALTER TABLE property_images DROP FOREIGN KEY FK_9E68D116549213EC');
        $this->addSql('DROP TABLE agents');
        $this->addSql('DROP TABLE amenities');
        $this->addSql('DROP TABLE locations');
        $this->addSql('DROP TABLE properties');
        $this->addSql('DROP TABLE property_amenities');
        $this->addSql('DROP TABLE property_images');
        $this->addSql('DROP TABLE users');
    }
}
