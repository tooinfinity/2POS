<?php

declare(strict_types=1);

namespace App\Helpers;

use JsonException;

final class TranslationHelper
{
    /**
     * @throws JsonException
     */
    public static function convertPhpToJson(string $locale): void
    {
        $translationPath = base_path("lang/{$locale}");
        $jsonPath = base_path("lang/{$locale}.json");
        $translations = [];

        if (is_dir($translationPath)) {
            $files = glob("{$translationPath}/*.php");
            if ($files !== false) {
                foreach ($files as $file) {
                    /** @var array<string, string> */
                    $fileTranslations = require $file;
                    $translations = array_merge($translations, $fileTranslations);
                }
            }
        }

        file_put_contents(
            $jsonPath,
            json_encode($translations, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );
    }
}
