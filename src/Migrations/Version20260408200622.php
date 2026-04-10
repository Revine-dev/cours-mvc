<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260408200622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Load initial test data for development environment';
    }

    public function up(Schema $schema): void
    {
        // 1. Agents
        $this->addSql("INSERT INTO agents (id, name, email, phone) VALUES
            (201, 'Claire Dubois', 'claire.dubois@agence.com', '+33 6 22 33 44 55'),
            (202, 'Antoine Leroy', 'antoine.leroy@agence.com', '+33 6 55 66 77 88'),
            (203, 'Julien Moreau', 'julien.moreau@agence.com', '+33 7 99 88 77 66'),
            (204, 'Sophie Bernard', 'sophie.bernard@agence.com', '+33 6 44 55 66 77')");

        // 2. Amenities
        $this->addSql("INSERT INTO amenities (id, name) VALUES
            (1, 'ascenseur'), (2, 'balcon'), (3, 'vue exceptionnelle'), (4, 'concierge'),
            (5, 'climatisation'), (6, 'piscine'), (7, 'vue mer'), (8, 'terrasse'),
            (9, 'domotique'), (10, 'garage double'), (11, 'hauteur sous plafond'),
            (12, 'poutres apparentes'), (13, 'lumière naturelle'), (14, 'cuisine équipée'),
            (15, 'emplacement premium'), (16, 'rentabilité élevée'), (17, 'immeuble sécurisé')");

        // 3. Locations
        $this->addSql("INSERT INTO locations (id, address, city, postal_code, country, latitude, longitude) VALUES
            (1, '15 Avenue Montaigne', 'Paris', '75008', 'France', 48.86600000, 2.30300000),
            (2, '8 Boulevard de la Croisette', 'Cannes', '06400', 'France', 43.55100000, 7.01200000),
            (3, '3 Place des Quinconces', 'Bordeaux', '33000', 'France', 44.84400000, -0.57300000),
            (4, '25 Rue du Faubourg Saint-Honoré', 'Paris', '75008', 'France', 48.87100000, 2.31600000)");

        // 4. Properties
        $this->addSql("INSERT INTO properties (id, title, slug, description, price, currency, type, status, created_at, updated_at, bedrooms, bathrooms, area, land_area, floor, floors, total_units, year_built, garage, parking, furnished, elevator, dpe, ges, location_id, agent_id) VALUES
            (1, 'Appartement de prestige avec vue Tour Eiffel', 'appartement-de-prestige-avec-vue-tour-eiffel', 'Appartement d’exception situé dans un immeuble haussmannien avec vue imprenable sur la Tour Eiffel.', 1850000.00, 'EUR', 'apartment', 'compromise', '2026-03-20 10:15:00', '2026-04-03 10:53:43', 3, 2, 120.00, NULL, 5, NULL, NULL, 1905, 0, 1, 1, NULL, 'C', 'C', 1, 201),
            (2, 'Villa contemporaine avec piscine sur la Côte d’Azur', 'villa-contemporaine-piscine-cote-azur', 'Superbe villa moderne avec piscine à débordement et vue mer panoramique.', 2450000.00, 'EUR', 'house', 'for_sale', '2026-03-18 09:00:00', '2026-03-22 11:45:00', 5, 4, 250.00, 1200.00, NULL, NULL, NULL, 2018, 1, 1, 1, NULL, 'A', 'A', 2, 202),
            (3, 'Loft design dans quartier historique', 'loft-design-quartier-historique', 'Magnifique loft rénové avec des matériaux haut de gamme dans un quartier prisé.', 720000.00, 'EUR', 'loft', 'for_sale', '2026-03-15 08:30:00', '2026-03-28 16:20:00', 2, 2, 95.00, NULL, 2, NULL, NULL, 1890, 0, 1, 0, NULL, 'B', 'A', 3, 203),
            (4, 'Immeuble de rapport en plein centre-ville', 'immeuble-rapport-centre-ville', 'Immeuble entier composé de 10 appartements loués, idéal pour investisseur avec excellente rentabilité.', 3200000.00, 'EUR', 'building', 'for_sale', '2026-03-10 12:00:00', '2026-03-29 10:00:00', NULL, NULL, 650.00, NULL, NULL, 5, 10, 1920, NULL, 0, NULL, 1, 'D', 'D', 4, 204)");

        // 5. Property Amenities
        $this->addSql("INSERT INTO property_amenities (property_id, amenity_id) VALUES
            (1,1), (1,2), (1,3), (1,4), (1,5), (2,6), (2,7), (2,8), (2,9), (2,10), (3,11), (3,12), (3,13), (3,14), (4,15), (4,16), (4,17)");

        // 6. Property Images
        $this->addSql("INSERT INTO property_images (id, property_id, image_url) VALUES
            (3, 2, 'https://placehold.co/800x600?text=Villa+1'),
            (4, 2, 'https://placehold.co/800x600?text=Villa+2'),
            (5, 3, 'https://placehold.co/800x600?text=Loft+1'),
            (6, 3, 'https://placehold.co/800x600?text=Loft+2'),
            (7, 4, 'https://placehold.co/800x600?text=Immeuble+1'),
            (8, 4, 'https://placehold.co/800x600?text=Immeuble+2'),
            (13, 1, 'https://placehold.co/800x600?text=Prestige'),
            (14, 1, 'https://placehold.co/800x600?text=Prestige+2')");

        // 7. Users
        $this->addSql("INSERT INTO users (id, name, email, password, role) VALUES
            (1, 'Johnny', 'admin@agence.com', '$2y$12$ytUzOLTuhiQruphdr.BOmuGhv8jeu66HcMOI3.0C6s/vaou2jJj3m', 'Admin')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM property_images');
        $this->addSql('DELETE FROM property_amenities');
        $this->addSql('DELETE FROM properties');
        $this->addSql('DELETE FROM locations');
        $this->addSql('DELETE FROM amenities');
        $this->addSql('DELETE FROM agents');
        $this->addSql('DELETE FROM users');
    }
}
