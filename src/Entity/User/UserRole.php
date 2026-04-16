<?php

declare(strict_types=1);

namespace App\Entity\User;

enum UserRole: string
{
    case ADMIN = 'Admin';
    case USER = 'User';
    case AGENT = 'Agent';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrateur',
            self::USER => 'Utilisateur',
            self::AGENT => 'Agent Immobilier',
        };
    }
}
