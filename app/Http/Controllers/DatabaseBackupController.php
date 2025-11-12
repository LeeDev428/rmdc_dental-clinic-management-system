<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use ZipArchive;

class DatabaseBackupController extends Controller
{
    /**
     * Show database backup page
     */
    public function index()
    {
        $backups = $this->getBackupList();
        return view('admin.database-backup', compact('backups'));
    }

    /**
     * Create database backup
     */
    public function create(Request $request)
    {
        try {
            $filename = 'rmdc_backup_' . Carbon::now()->format('Y-m-d_His') . '.sql';
            $path = storage_path('app/backups/' . $filename);

            // Create backup directory if it doesn't exist
            if (!file_exists(storage_path('app/backups'))) {
                mkdir(storage_path('app/backups'), 0755, true);
            }

            // Get database configuration
            $dbHost = config('database.connections.mysql.host');
            $dbName = config('database.connections.mysql.database');
            $dbUser = config('database.connections.mysql.username');
            $dbPass = config('database.connections.mysql.password');

            // Create mysqldump command
            $command = sprintf(
                'mysqldump --user=%s --password=%s --host=%s %s > %s',
                escapeshellarg($dbUser),
                escapeshellarg($dbPass),
                escapeshellarg($dbHost),
                escapeshellarg($dbName),
                escapeshellarg($path)
            );

            // Execute backup
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                throw new \Exception('Database backup failed');
            }

            // Create metadata file
            $metadata = [
                'filename' => $filename,
                'created_at' => Carbon::now()->toDateTimeString(),
                'size' => filesize($path),
                'tables' => $this->getTableCount(),
                'created_by' => auth()->user()->name,
            ];

            file_put_contents(
                storage_path('app/backups/' . str_replace('.sql', '.json', $filename)),
                json_encode($metadata, JSON_PRETTY_PRINT)
            );

            // Log activity
            Log::info('Database backup created', [
                'user' => auth()->user()->name,
                'filename' => $filename,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Database backup created successfully',
                'filename' => $filename,
            ]);

        } catch (\Exception $e) {
            Log::error('Database backup failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Backup failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Download backup file
     */
    public function download($filename)
    {
        $path = storage_path('app/backups/' . $filename);

        if (!file_exists($path)) {
            abort(404, 'Backup file not found');
        }

        return response()->download($path);
    }

    /**
     * Delete backup file
     */
    public function delete($filename)
    {
        try {
            $sqlPath = storage_path('app/backups/' . $filename);
            $jsonPath = storage_path('app/backups/' . str_replace('.sql', '.json', $filename));

            if (file_exists($sqlPath)) {
                unlink($sqlPath);
            }

            if (file_exists($jsonPath)) {
                unlink($jsonPath);
            }

            Log::info('Database backup deleted', [
                'user' => auth()->user()->name,
                'filename' => $filename,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup deleted successfully',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Delete failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create automatic scheduled backup
     */
    public function autoBackup()
    {
        try {
            $this->create(new Request());
            
            // Clean old backups (keep only last 30 days)
            $this->cleanOldBackups(30);

            return response()->json([
                'success' => true,
                'message' => 'Auto backup completed',
            ]);

        } catch (\Exception $e) {
            Log::error('Auto backup failed', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get list of backup files
     */
    private function getBackupList()
    {
        $backupDir = storage_path('app/backups');
        $backups = [];

        if (!file_exists($backupDir)) {
            return $backups;
        }

        $files = scandir($backupDir);

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $jsonFile = str_replace('.sql', '.json', $file);
                $metadata = [];

                if (file_exists($backupDir . '/' . $jsonFile)) {
                    $metadata = json_decode(file_get_contents($backupDir . '/' . $jsonFile), true);
                }

                $backups[] = [
                    'filename' => $file,
                    'size' => filesize($backupDir . '/' . $file),
                    'created_at' => $metadata['created_at'] ?? date('Y-m-d H:i:s', filemtime($backupDir . '/' . $file)),
                    'tables' => $metadata['tables'] ?? 0,
                    'created_by' => $metadata['created_by'] ?? 'Unknown',
                ];
            }
        }

        // Sort by creation date (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $backups;
    }

    /**
     * Get table count
     */
    private function getTableCount()
    {
        return count(DB::select('SHOW TABLES'));
    }

    /**
     * Clean old backups
     */
    private function cleanOldBackups($days = 30)
    {
        $backupDir = storage_path('app/backups');
        $files = scandir($backupDir);
        $now = time();

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                $filePath = $backupDir . '/' . $file;
                $fileAge = ($now - filemtime($filePath)) / (60 * 60 * 24); // Age in days

                if ($fileAge > $days) {
                    unlink($filePath);
                    
                    // Delete metadata file too
                    $jsonFile = str_replace('.sql', '.json', $file);
                    if (file_exists($backupDir . '/' . $jsonFile)) {
                        unlink($backupDir . '/' . $jsonFile);
                    }
                }
            }
        }
    }
}
