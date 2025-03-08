<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Setting> */
final class SettingFactory extends Factory
{
    /** @var class-string<Setting> */
    protected $model = Setting::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->word(),
            'value' => $this->faker->word(),
        ];
    }
}
