<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case Administrator = 'Administrator';
    case Manager = 'Manager';
    case Cashier = 'Cashier';

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array<int, string>
     */
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function isModifiable(string $role): bool
    {
        return $role !== self::Administrator->value;
    }
}
