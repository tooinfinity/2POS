<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use HasFactory;

    use HasUlids;

    protected $primaryKey = 'ulid';

    protected $fillable = ['key', 'value'];

    public static function get(string $key, string $default = ''): string
    {
        $setting = self::query()->where('key', $key)->first();

        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, string $value): void
    {
        self::query()->updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }
}
