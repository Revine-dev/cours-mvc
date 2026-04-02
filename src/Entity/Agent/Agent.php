<?php

declare(strict_types=1);

namespace App\Entity\Agent;

use App\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'agents')]
class Agent implements Entity
{
    #[ORM\Id]
    #[ORM\Column(type: 'integer')]
    public int $id;

    #[ORM\Column(type: 'string')]
    public string $name;

    #[ORM\Column(type: 'string', unique: true)]
    public string $email;

    #[ORM\Column(type: 'string', nullable: true)]
    public ?string $phone = null;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ];
    }

    #[\ReturnTypeWillChange]
    public function toData(): array
    {
        return $this->toArray();
    }
}
