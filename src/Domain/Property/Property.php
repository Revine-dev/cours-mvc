<?php

declare(strict_types=1);

namespace App\Domain\Property;

use JsonSerializable;

class Property implements JsonSerializable
{
    public int $id;
    public string $title;
    public string $slug;
    public string $description;
    public int $price;
    public string $currency;
    public string $type;
    public string $status;
    public string $created_at;
    public string $updated_at;
    public array $location;
    public array $features;
    public array $energy;
    public array $amenities;
    public array $images;
    public array $agent;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->title = $data['title'] ?? '';
        $this->slug = $data['slug'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->price = $data['price'] ?? 0;
        $this->currency = $data['currency'] ?? 'EUR';
        $this->type = $data['type'] ?? '';
        $this->status = $data['status'] ?? '';
        $this->created_at = $data['created_at'] ?? '';
        $this->updated_at = $data['updated_at'] ?? '';
        $this->location = $data['location'] ?? [];
        $this->features = $data['features'] ?? [];
        $this->energy = $data['energy'] ?? [];
        $this->amenities = $data['amenities'] ?? [];
        $this->images = $data['images'] ?? [];
        $this->agent = $data['agent'] ?? [];
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
