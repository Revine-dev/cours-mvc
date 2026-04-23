<?php

declare(strict_types=1);

namespace App\Entity\Property;

use App\Entity\Entity;
use App\Entity\Location\Location;
use App\Entity\Agent\Agent;
use App\Entity\Amenity\Amenity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @property array $images
 * @property-read array $images_with_placeholder
 * @property string|float $price
 * @property string|float $area
 * @property string|float $landArea
 * @property array $features
 * @property array $energy
 * @property \App\Entity\Location\Location|null $location
 * @property \App\Entity\Agent\Agent|null $agent
 */
#[ORM\Entity]
#[ORM\Table(name: 'properties')]
class Property implements Entity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'string')]
    public string $title = '';

    #[ORM\Column(type: 'string', unique: true)]
    public string $slug = '';

    #[ORM\Column(type: 'text')]
    public string $description = '';

    #[ORM\Column(type: 'decimal', precision: 15, scale: 2)]
    protected string $price = '0';

    #[ORM\Column(type: 'string', length: 10)]
    public string $currency = 'EUR';

    #[ORM\Column(type: 'string', length: 50)]
    public string $type = '';

    #[ORM\Column(type: 'string', length: 50)]
    public string $status = '';

    #[ORM\Column(type: 'datetime', name: 'created_at')]
    public \DateTime $createdAt;

    #[ORM\Column(type: 'datetime', name: 'updated_at')]
    public ?\DateTime $updatedAt;

    /**
     * @var array|null Transient images for preview
     */
    public ?array $tempImages = null;

    // --- Features ---
    #[ORM\Column(type: 'integer', nullable: true)]
    public ?int $bedrooms = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    public ?int $bathrooms = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    protected ?string $area = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true, name: 'land_area')]
    protected ?string $landArea = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    public ?int $floor = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    public ?int $floors = null;

    #[ORM\Column(type: 'integer', nullable: true, name: 'total_units')]
    public ?int $totalUnits = null;

    #[ORM\Column(type: 'integer', nullable: true, name: 'year_built')]
    public ?int $yearBuilt = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    public ?bool $garage = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    public ?bool $parking = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    public ?bool $furnished = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    public ?bool $elevator = null;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    public ?string $dpe = null;

    #[ORM\Column(type: 'string', length: 5, nullable: true)]
    public ?string $ges = null;

    // --- Relationships ---

    #[ORM\ManyToOne(targetEntity: Location::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'location_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected ?Location $location = null;

    #[ORM\ManyToOne(targetEntity: Agent::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'agent_id', referencedColumnName: 'id', onDelete: 'SET NULL')]
    protected ?Agent $agent = null;

    /**
     * @var Collection
     */
    #[ORM\OneToMany(targetEntity: PropertyImage::class, mappedBy: 'property', cascade: ['persist', 'remove'], orphanRemoval: true)]
    protected Collection $propertyImages;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: Amenity::class, inversedBy: 'properties')]
    #[ORM\JoinTable(name: 'property_amenities')]
    #[ORM\JoinColumn(name: 'property_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'amenity_id', referencedColumnName: 'id')]
    protected Collection $amenities;

    public function __construct()
    {
        $this->propertyImages = new ArrayCollection();
        $this->amenities = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * Magic getter for backward compatibility and safety.
     */
    public function __get(string $name): mixed
    {
        if ($name === 'images') {
            if (!isset($this->propertyImages) || $this->propertyImages->isEmpty()) {
                return [];
            }
            return array_map(fn($img) => $img->imageUrl, $this->propertyImages->toArray());
        }

        if ($name === 'images_with_placeholder') {
            $images = $this->__get('images');
            return empty($images) ? ['https://placehold.co/800x600?text=No+Image'] : $images;
        }

        if ($name === 'location') {
            return $this->location ?? new Location();
        }

        if ($name === 'agent') {
            return $this->agent ?? new Agent();
        }

        if ($name === 'price') {
            return (float) ($this->price ?? 0);
        }

        if ($name === 'features') {
            return [
                'bedrooms' => $this->bedrooms ?? 0,
                'bathrooms' => $this->bathrooms ?? 0,
                'area' => (float) ($this->area ?? 0),
                'land_area' => (float) ($this->landArea ?? 0),
                'floor' => $this->floor ?? null,
                'floors' => $this->floors ?? null,
                'total_units' => $this->totalUnits ?? null,
                'year_built' => $this->yearBuilt ?? null,
                'garage' => $this->garage ?? false,
                'parking' => $this->parking ?? false,
                'furnished' => $this->furnished ?? false,
                'elevator' => $this->elevator ?? false,
            ];
        }

        if ($name === 'energy') {
            return [
                'dpe' => $this->dpe ?? '',
                'ges' => $this->ges ?? '',
            ];
        }

        // Access protected properties if they exist
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        return null;
    }

    /**
     * Magic setter for backward compatibility and ease of use.
     */
    public function __set(string $name, mixed $value): void
    {
        if ($name === 'images') {
            $this->tempImages = (array) $value;
            return;
        }

        if ($name === 'price') {
            $this->price = (string) $value;
            return;
        }

        if ($name === 'area') {
            $this->area = (string) $value;
            return;
        }

        if ($name === 'landArea') {
            $this->landArea = (string) $value;
            return;
        }

        if ($name === 'features') {
            if (is_array($value)) {
                $this->bedrooms = isset($value['bedrooms']) ? (int) $value['bedrooms'] : $this->bedrooms;
                $this->bathrooms = isset($value['bathrooms']) ? (int) $value['bathrooms'] : $this->bathrooms;
                $this->area = isset($value['area']) ? (string) $value['area'] : $this->area;
                $this->landArea = isset($value['land_area']) ? (string) $value['land_area'] : $this->landArea;
                $this->floor = isset($value['floor']) ? (int) $value['floor'] : $this->floor;
                $this->floors = isset($value['floors']) ? (int) $value['floors'] : $this->floors;
                $this->totalUnits = isset($value['total_units']) ? (int) $value['total_units'] : $this->totalUnits;
                $this->yearBuilt = isset($value['year_built']) ? (int) $value['year_built'] : $this->yearBuilt;
                $this->garage = isset($value['garage']) ? (bool) $value['garage'] : $this->garage;
                $this->parking = isset($value['parking']) ? (bool) $value['parking'] : $this->parking;
                $this->furnished = isset($value['furnished']) ? (bool) $value['furnished'] : $this->furnished;
                $this->elevator = isset($value['elevator']) ? (bool) $value['elevator'] : $this->elevator;
            }
            return;
        }

        if (property_exists($this, $name)) {
            $this->$name = $value;
        }
    }

    public function setImages(array $imageUrls): void
    {
        $this->propertyImages->clear();
        foreach ($imageUrls as $url) {
            if (empty($url)) {
                continue;
            }
            $image = new PropertyImage();
            $image->imageUrl = $url;
            $image->setProperty($this);
            $this->propertyImages->add($image);
        }
    }

    /**
     * Magic isset for compatibility.
     */
    public function __isset(string $name): bool
    {
        if (in_array($name, ['images', 'location', 'agent', 'price', 'features', 'energy'])) {
            return true;
        }
        return property_exists($this, $name);
    }

    /**
     * Hydrate the property from an array of data safely.
     */
    public function fromArray(array $data): void
    {
        $this->title = (string)($data['title'] ?? $this->title);
        $this->description = (string)($data['description'] ?? $this->description);
        $this->price = (string)($data['price'] ?? $this->price);
        $this->status = (string)($data['status'] ?? $this->status);
        $this->type = (string)($data['type'] ?? $this->type);
        $this->currency = (string)($data['currency'] ?? $this->currency);

        if (isset($data['slug']) && !empty($data['slug'])) {
            $this->slug = (string)$data['slug'];
        }

        if (isset($data['features']) && is_array($data['features'])) {
            $this->features = $data['features'];
        }

        if (isset($data['address']) || isset($data['city']) || isset($data['postal_code'])) {
            if (!$this->location) {
                $this->location = new Location();
            }
            $this->location->address = (string)($data['address'] ?? $this->location->address);
            $this->location->city = (string)($data['city'] ?? $this->location->city);
            $this->location->postal_code = (string)($data['postal_code'] ?? $this->location->postal_code);
            $this->location->country = (string)($data['country'] ?? 'France');
        }

        if (isset($data['images']) && is_array($data['images'])) {
            $this->setImages($data['images']);
        }

        $this->updatedAt = new \DateTime();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title ?? '',
            'slug' => $this->slug ?? '',
            'description' => $this->description ?? '',
            'price' => (float) ($this->price ?? 0),
            'currency' => $this->currency ?? 'EUR',
            'type' => $this->type ?? '',
            'status' => $this->status ?? '',
            'created_at' => isset($this->createdAt) ? $this->createdAt->format(\DateTime::ATOM) : '',
            'updated_at' => isset($this->updatedAt) ? $this->updatedAt->format(\DateTime::ATOM) : '',
            'location' => $this->location ? $this->location->toArray() : [
                'address' => '',
                'city' => '',
                'postal_code' => '',
                'country' => '',
                'latitude' => null,
                'longitude' => null
            ],
            'agent' => $this->agent ? $this->agent->toArray() : [
                'id' => null,
                'name' => '',
                'email' => '',
                'phone' => ''
            ],
            'images' => $this->__get('images'),
            'amenities' => isset($this->amenities) ? array_map(fn($amn) => $amn->name, $this->amenities->toArray()) : [],
            'features' => [
                'bedrooms' => $this->bedrooms ?? 0,
                'bathrooms' => $this->bathrooms ?? 0,
                'area' => (float) ($this->area ?? 0),
                'land_area' => (float) ($this->landArea ?? 0),
                'floor' => $this->floor ?? null,
                'floors' => $this->floors ?? null,
                'total_units' => $this->totalUnits ?? null,
                'year_built' => $this->yearBuilt ?? null,
                'garage' => $this->garage ?? false,
                'parking' => $this->parking ?? false,
                'furnished' => $this->furnished ?? false,
                'elevator' => $this->elevator ?? false,
            ],
            'energy' => [
                'dpe' => $this->dpe ?? '',
                'ges' => $this->ges ?? '',
            ],
        ];
    }

    public function toData(): array
    {
        return $this->toArray();
    }
}
