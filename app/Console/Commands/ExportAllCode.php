<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportAllCode extends Command
{
    protected $signature = 'export:code';
    protected $description = 'Export all Laravel project code into one file';

    public function handle()
    {
        $paths = [
            base_path('app'),
            base_path('routes'),
            base_path('resources/views'),
            base_path('config'),
            base_path('database/migrations'),
        ];

        $outputFile = storage_path('app/full_code_export.txt');
        File::put($outputFile, "=== Laravel Project Code Export ===\n\n");

        foreach ($paths as $path) {
            $files = File::allFiles($path);
            foreach ($files as $file) {
                $relativePath = str_replace(base_path(), '', $file->getRealPath());
                File::append($outputFile, "\n\n===== FILE: $relativePath =====\n");
                File::append($outputFile, File::get($file));
            }
        }

        $this->info("✅ All code exported to: $outputFile");
    }
}
