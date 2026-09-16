<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

class VerifyNamedRoutesCommand extends Command
{
    protected $signature = 'app:verify-routes';

    protected $description = 'Verify named routes referenced in Blade views are registered';

    public function handle(): int
    {
        $paths = array_merge(
            File::glob(resource_path('views/frontend/**/*.blade.php')) ?: [],
            File::glob(resource_path('views/frontend/**/**/*.blade.php')) ?: [],
            File::glob(resource_path('views/frontend/**/**/**/*.blade.php')) ?: []
        );

        $paths = array_unique($paths);
        $missing = [];

        foreach ($paths as $file) {
            $content = File::get($file);
            if (!preg_match_all("/route\\(['\\\"]([^'\\\"]+)['\\\"]/", $content, $matches)) {
                continue;
            }

            foreach (array_unique($matches[1]) as $routeName) {
                if (str_starts_with($routeName, 'yonetim.')) {
                    continue;
                }

                if (!Route::has($routeName)) {
                    $missing[$routeName][] = str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file);
                }
            }
        }

        if ($missing === []) {
            $this->info('All frontend named routes are registered.');

            return self::SUCCESS;
        }

        $this->error('Missing route definitions:');
        foreach ($missing as $routeName => $files) {
            $this->line("  - {$routeName}");
            foreach (array_unique($files) as $file) {
                $this->line("      {$file}");
            }
        }

        return self::FAILURE;
    }
}
