<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case SuperAdministrator = 'Super Administrator';

    case Manager = 'Manager';
    case Cashier = 'Cashier';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function isModifiable(string $role): bool
    {
        return $role !== self::SuperAdministrator->value;
    }
}
