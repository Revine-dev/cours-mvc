<?php

declare(strict_types=1);

namespace App\Entity\Property;

use App\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'property_images')]
class PropertyImage implements Entity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Property::class, inversedBy: 'propertyImages')]
    #[ORM\JoinColumn(name: 'property_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Property $property;

    #[ORM\Column(name: 'image_url', type: 'string', length: 500)]
    public string $imageUrl;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'image_url' => $this->imageUrl,
        ];
    }

    #[\ReturnTypeWillChange]
    public function toData(): array
    {
        return $this->toArray();
    }

    public function setProperty(Property $property): void
    {
        $this->property = $property;
    }
}
