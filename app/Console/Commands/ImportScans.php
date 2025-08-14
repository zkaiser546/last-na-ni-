<?php

namespace App\Console\Commands;

use App\Models\Scan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportScans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scan:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import new scans from ScanSnap Home output folder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $outputPath = env('SCANSNAP_OUTPUT_PATH');

        if (!$outputPath) {
            $this->error('SCANSNAP_OUTPUT_PATH not configured in .env file');
            return 1;
        }

        if (!File::exists($outputPath)) {
            $this->error("ScanSnap output path does not exist: {$outputPath}");
            return 1;
        }

        // Ensure the storage directory exists
        Storage::makeDirectory('public/scans');

        $supportedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        $importedCount = 0;
        $skippedCount = 0;

        // Get all files from the output directory
        $files = File::files($outputPath);

        $this->info("Checking {$outputPath} for new scans...");

        foreach ($files as $file) {
            $extension = strtolower($file->getExtension());

            // Skip unsupported file types
            if (!in_array($extension, $supportedExtensions)) {
                continue;
            }

            $originalFilename = $file->getFilename();

            // Check if file is already imported
            if (Scan::where('original_filename', $originalFilename)->exists()) {
                $skippedCount++;
                continue;
            }

            try {
                // Generate a unique filename for storage
                $storedFilename = Str::uuid() . '.' . $extension;
                $storagePath = 'public/scans/' . $storedFilename;

                // Copy file to storage
                Storage::put($storagePath, File::get($file->getRealPath()));

                // Create database record
                Scan::create([
                    'filename' => $storedFilename,
                    'original_filename' => $originalFilename,
                    'mime_type' => $this->getMimeType($extension),
                    'file_size' => $file->getSize(),
                ]);

                // Remove the original file from the output directory
                File::delete($file->getRealPath());

                $importedCount++;
                $this->info("Imported: {$originalFilename}");

            } catch (\Exception $e) {
                $this->error("Failed to import {$originalFilename}: " . $e->getMessage());
            }
        }

        $this->info("Import completed: {$importedCount} imported, {$skippedCount} skipped");
        return 0;
    }

    /**
     * Get MIME type based on file extension
     */
    private function getMimeType($extension): string
    {
        return match ($extension) {
            'pdf' => 'application/pdf',
            'jpg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            default => 'application/octet-stream',
        };
    }
}
