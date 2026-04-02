<?php

declare(strict_types=1);

namespace App\Entity\Location;

use App\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'locations')]
class Location implements Entity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'string')]
    public string $address = '';

    #[ORM\Column(type: 'string')]
    public string $city = '';

    #[ORM\Column(type: 'string', name: 'postal_code')]
    public string $postal_code = '';

    #[ORM\Column(type: 'string')]
    public string $country = '';

    #[ORM\Column(type: 'decimal', precision: 10, scale: 8, nullable: true)]
    public ?string $latitude = null;

    #[ORM\Column(type: 'decimal', precision: 11, scale: 8, nullable: true)]
    public ?string $longitude = null;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'address' => $this->address,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'country' => $this->country,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
    }

    #[\ReturnTypeWillChange]
    public function toData(): array
    {
        return $this->toArray();
    }
}
