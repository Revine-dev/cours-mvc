<?php

declare(strict_types=1);

namespace App\Entity\Amenity;

use App\Entity\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'amenities')]
class Amenity implements Entity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    public ?int $id = null;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
    public string $name;

    /**
     * @var Collection
     */
    #[ORM\ManyToMany(targetEntity: 'App\Entity\Property\Property', mappedBy: 'amenities')]
    public Collection $properties;

    public function __construct()
    {
        $this->properties = new ArrayCollection();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    #[\ReturnTypeWillChange]
    public function toData(): array
    {
        return $this->toArray();
    }
}
