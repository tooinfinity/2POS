<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Helpers\TranslationHelper;
use Illuminate\Console\Command;
use JsonException;

final class ConvertTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:convert {locale?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert PHP translation files to JSON';

    /**
     * Execute the console command.
     *
     * @throws JsonException
     */
    public function handle(): void
    {
        $locale = $this->argument('locale');

        if ($locale) {
            TranslationHelper::convertPhpToJson($locale);
            $this->info("Converted translations for {$locale}");
        } else {
            $dirs = glob(base_path('lang/*'), GLOB_ONLYDIR);

            if ($dirs === [] || $dirs === false) {
                $this->error('No translation directories found');

                return;
            }
            foreach ($dirs as $dir) {
                $locale = basename($dir);
                TranslationHelper::convertPhpToJson($locale);
                $this->info("Converted translations for {$locale}");
            }
        }

        $this->line('');

        $this->info('All translations have been converted successfully');

        $this->line('');
    }
}
