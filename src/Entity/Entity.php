<?php

declare(strict_types=1);

namespace App\Entity;

/**
 * Interface for all entities.
 */
interface Entity
{
    /**
     * Convert the entity to a data array.
     */
    public function toData(): array;

    /**
     * Convert the entity to a plain array (legacy).
     */
    public function toArray(): array;
}
