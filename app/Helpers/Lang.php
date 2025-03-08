<?php

declare(strict_types=1);

/**
 * @return array<string, string>
 *
 * @throws JsonException
 */
function translations(string $file): array
{
    if (! file_exists($file)) {
        return [];
    }

    $contents = file_get_contents($file);
    if ($contents === false) {
        return [];
    }

    /** @var array<string, string> */
    $translations = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);

    return $translations;
}
