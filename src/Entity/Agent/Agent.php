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

    #[ORM\Column(type: 'string', length: 100)]
    public string $name;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    public string $email;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    public ?string $phone = null;

    /**
     * Hydrate the agent from an array of data safely.
     */
    public function fromArray(array $data): void
    {
        if (isset($data['id'])) {
            $this->id = (int)$data['id'];
        }
        $this->name = (string)($data['name'] ?? $this->name);
        $this->email = (string)($data['email'] ?? $this->email);
        $this->phone = (string)($data['phone'] ?? $this->phone);
    }

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
