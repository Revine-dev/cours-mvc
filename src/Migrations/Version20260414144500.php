<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migration to add more exceptional properties in Paris, Lyon and Bordeaux.
 */
final class Version20260414144500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add 10 more exceptional properties for testing pagination and search';
    }

    public function up(Schema $schema): void
    {
        $this->skipIf(getenv('ENV') !== 'dev', 'Skipping test data migration. Set ENV=dev to execute.');

        // 1. New Locations (IDs 10 to 19)
        $this->addSql("INSERT INTO locations (id, address, city, postal_code, country, latitude, longitude) VALUES
            (10, '12 Rue de Passy', 'Paris', '75016', 'France', 48.858000, 2.283000),
            (11, '5 Rue de Rivoli', 'Paris', '75004', 'France', 48.855000, 2.358000),
            (12, '10 Place Bellecour', 'Lyon', '69002', 'France', 45.757000, 4.832000),
            (13, '22 Avenue Foch', 'Lyon', '69006', 'France', 45.772000, 4.845000),
            (14, '15 Rue Saint-Jean', 'Lyon', '69005', 'France', 45.762000, 4.827000),
            (15, '45 Allées de Tourny', 'Bordeaux', '33000', 'France', 44.843000, -0.575000),
            (16, '8 Quai des Chartrons', 'Bordeaux', '33000', 'France', 44.852000, -0.570000),
            (17, '1 Place Vendôme', 'Paris', '75001', 'France', 48.867000, 2.329000),
            (18, '30 Avenue de la Bourdonnais', 'Paris', '75007', 'France', 48.858000, 2.297000),
            (19, '12 Rue du Jardin Public', 'Bordeaux', '33300', 'France', 44.848000, -0.578000)");

        // 2. New Properties (IDs 10 to 19)
        $this->addSql("INSERT INTO properties (id, title, slug, description, price, currency, type, status, created_at, updated_at, bedrooms, bathrooms, area, land_area, floor, floors, total_units, year_built, garage, parking, furnished, elevator, dpe, ges, location_id, agent_id) VALUES
            (10, 'Penthouse avec terrasse panoramique', 'penthouse-terrasse-panoramique-paris', 'Un penthouse d’exception au coeur du 16ème arrondissement.', 4200000.00, 'EUR', 'apartment', 'for_sale', '2026-04-01 10:00:00', '2026-04-01 10:00:00', 4, 3, 180.00, NULL, 8, NULL, NULL, 1990, 1, 1, 1, 1, 'B', 'B', 10, 201),
            (11, 'Hôtel particulier historique Marais', 'hotel-particulier-historique-marais', 'Demeure historique du XVIIème siècle entièrement rénovée.', 8500000.00, 'EUR', 'house', 'for_sale', '2026-04-02 11:30:00', '2026-04-02 11:30:00', 8, 6, 450.00, 200.00, NULL, 3, NULL, 1680, 0, 0, 0, 0, 'E', 'E', 11, 202),
            (12, 'Appartement bourgeois Place Bellecour', 'appartement-bourgeois-bellecour-lyon', 'Superbe appartement avec vue sur la place Bellecour.', 1250000.00, 'EUR', 'apartment', 'for_sale', '2026-04-03 09:15:00', '2026-04-03 09:15:00', 3, 2, 140.00, NULL, 3, NULL, NULL, 1850, 0, 1, 1, 1, 'C', 'D', 12, 203),
            (13, 'Maison d’architecte Parc de la Tête d’Or', 'maison-architecte-parc-lyon', 'Une réalisation contemporaine aux prestations haut de gamme.', 2100000.00, 'EUR', 'house', 'for_sale', '2026-04-04 14:00:00', '2026-04-04 14:00:00', 4, 3, 220.00, 500.00, NULL, 2, NULL, 2022, 1, 2, 0, 0, 'A', 'A', 13, 204),
            (14, 'Canut rénové Vieux Lyon', 'canut-renove-vieux-lyon', 'Authenticité et modernité pour cet ancien atelier de soyeux.', 680000.00, 'EUR', 'loft', 'for_sale', '2026-04-05 16:45:00', '2026-04-05 16:45:00', 2, 1, 110.00, NULL, 4, NULL, NULL, 1830, 0, 0, 1, 0, 'D', 'C', 14, 201),
            (15, 'Appartement de luxe Triangle d’Or', 'appartement-luxe-triangle-or-bordeaux', 'Au dernier étage d’un immeuble pierre de taille, calme absolu.', 1350000.00, 'EUR', 'apartment', 'compromise', '2026-04-06 10:20:00', '2026-04-06 10:20:00', 3, 2, 135.00, NULL, 4, NULL, NULL, 1880, 0, 1, 1, 1, 'C', 'C', 15, 202),
            (16, 'Loft industriel Chartrons', 'loft-industriel-chartrons-bordeaux', 'Volumes spectaculaires et patio intérieur pour ce loft unique.', 980000.00, 'EUR', 'loft', 'for_sale', '2026-04-07 12:00:00', '2026-04-07 12:00:00', 3, 2, 165.00, NULL, 0, NULL, NULL, 1950, 1, 1, 0, 0, 'C', 'B', 16, 203),
            (17, 'Appartement de maître Place Vendôme', 'appartement-maitre-place-vendome', 'L’adresse la plus prestigieuse de Paris pour cet écrin de luxe.', 12000000.00, 'EUR', 'apartment', 'for_sale', '2026-04-08 08:00:00', '2026-04-08 08:00:00', 4, 4, 310.00, NULL, 2, NULL, NULL, 1720, 0, 1, 1, 1, 'D', 'D', 17, 204),
            (18, 'Vue imprenable Champ de Mars', 'appartement-vue-champ-de-mars', 'Face à la Tour Eiffel, un appartement de réception unique.', 5600000.00, 'EUR', 'apartment', 'for_sale', '2026-04-09 11:00:00', '2026-04-09 11:00:00', 3, 3, 215.00, NULL, 4, NULL, NULL, 1910, 0, 1, 1, 1, 'D', 'E', 18, 201),
            (19, 'Échoppe bordelaise revisitée', 'echoppe-bordelaise-revisitee', 'Charme de l’ancien et extension contemporaine avec jardin.', 890000.00, 'EUR', 'house', 'for_sale', '2026-04-10 15:30:00', '2026-04-10 15:30:00', 4, 2, 145.00, 120.00, NULL, 2, NULL, 1900, 0, 1, 0, 0, 'C', 'C', 19, 202)");

        // 3. New Property Images (IDs 100 to 119)
        $this->addSql("INSERT INTO property_images (id, property_id, image_url) VALUES
            (100, 10, 'https://placehold.co/800x600?text=Penthouse+Paris'),
            (101, 11, 'https://placehold.co/800x600?text=Hotel+Particulier'),
            (102, 12, 'https://placehold.co/800x600?text=Bellecour+Lyon'),
            (103, 13, 'https://placehold.co/800x600?text=Maison+Parc+Lyon'),
            (104, 14, 'https://placehold.co/800x600?text=Canut+Lyon'),
            (105, 15, 'https://placehold.co/800x600?text=Triangle+Or+Bordeaux'),
            (106, 16, 'https://placehold.co/800x600?text=Loft+Chartrons'),
            (107, 17, 'https://placehold.co/800x600?text=Vendome+Paris'),
            (108, 18, 'https://placehold.co/800x600?text=Champ+de+Mars'),
            (109, 19, 'https://placehold.co/800x600?text=Echoppe+Bordeaux')");
    }

    public function down(Schema $schema): void
    {
        $this->skipIf(getenv('ENV') !== 'dev', 'Skipping test data rollback.');

        $this->addSql('DELETE FROM property_images WHERE id >= 100');
        $this->addSql('DELETE FROM properties WHERE id >= 10; ');
        $this->addSql('DELETE FROM locations WHERE id >= 10');
    }
}
