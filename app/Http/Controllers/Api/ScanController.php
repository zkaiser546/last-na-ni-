<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Scan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ScanController extends Controller
{
    /**
     * Get all scans
     */
    public function index(): JsonResponse
    {
        $scans = Scan::orderBy('created_at', 'desc')->get();

        return response()->json([
            'data' => $scans->map(function ($scan) {
                return [
                    'id' => $scan->id,
                    'original_filename' => $scan->original_filename,
                    'public_url' => $scan->public_url,
                    'mime_type' => $scan->mime_type,
                    'file_size' => $scan->file_size,
                    'created_at' => $scan->created_at,
                ];
            })
        ]);
    }

    /**
     * Manually trigger scan import
     */
    public function import(): JsonResponse
    {
        try {
            // Run the scan:import command
            $exitCode = Artisan::call('scan:import');

            if ($exitCode === 0) {
                return response()->json([
                    'message' => 'Import completed successfully',
                    'output' => Artisan::output()
                ]);
            } else {
                return response()->json([
                    'message' => 'Import failed',
                    'output' => Artisan::output()
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Import failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a scan
     */
    public function destroy(Scan $scan): JsonResponse
    {
        try {
            $scan->deleteWithFile();

            return response()->json([
                'message' => 'Scan deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete scan'
            ], 500);
        }
    }
}
