<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Scan extends Model
{
    use HasFactory;

    protected $fillable = [
        'filename',
        'original_filename',
        'mime_type',
        'file_size',
    ];

    protected $appends = ['public_url'];

    /**
     * Get the public URL for the scan file
     */
    public function getPublicUrlAttribute(): string
    {
        return Storage::url('scans/' . $this->filename);
    }

    /**
     * Get the full storage path for the scan file
     */
    public function getStoragePathAttribute(): string
    {
        return storage_path('app/public/scans/' . $this->filename);
    }

    /**
     * Delete the scan and its associated file
     */
    public function deleteWithFile(): bool
    {
        // Delete the file from storage
        if (Storage::exists('public/scans/' . $this->filename)) {
            Storage::delete('public/scans/' . $this->filename);
        }

        // Delete the database record
        return $this->delete();
    }
}
