<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use ZipArchive;
use App\Traits\LogsActivity;

class DatabaseBackupController extends Controller
{
    use LogsActivity;
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
            $dbPort = config('database.connections.mysql.port', 3306);

            // Use PHP method directly to avoid mysqldump authentication plugin issues
            // mysqldump often fails with caching_sha2_password error on MySQL 8+
            return $this->createBackupWithPHP($path, $filename);

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

    /**
     * Find mysqldump executable
     */
    private function findMysqldump()
    {
        $paths = [
            'C:\\xampp\\mysql\\bin\\mysqldump.exe',
            'C:\\laragon\\bin\\mysql\\mysql-8.0.30-winx64\\bin\\mysqldump.exe',
            'C:\\Program Files\\MySQL\\MySQL Server 8.0\\bin\\mysqldump.exe',
            '/usr/bin/mysqldump',
            '/usr/local/bin/mysqldump',
        ];

        foreach ($paths as $path) {
            if (file_exists($path)) {
                return $path;
            }
        }

        // Try to find in PATH
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec('where mysqldump 2>nul', $output, $returnVar);
        } else {
            exec('which mysqldump', $output, $returnVar);
        }

        return $returnVar === 0 && !empty($output[0]) ? $output[0] : null;
    }

    /**
     * Create backup using PHP (fallback method)
     */
    private function createBackupWithPHP($path, $filename)
    {
        try {
            $dbName = config('database.connections.mysql.database');
            
            // Get all tables
            $tables = DB::select('SHOW TABLES');
            $tableKey = 'Tables_in_' . $dbName;
            
            $sql = "-- Database Backup\n";
            $sql .= "-- Generated: " . Carbon::now()->toDateTimeString() . "\n";
            $sql .= "-- Database: {$dbName}\n\n";
            $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                
                // Get table structure
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sql .= "-- Table: {$tableName}\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";

                // Get table data
                $rows = DB::table($tableName)->get();
                
                if ($rows->count() > 0) {
                    $sql .= "-- Data for table {$tableName}\n";
                    
                    foreach ($rows as $row) {
                        $values = [];
                        foreach ((array)$row as $value) {
                            if ($value === null) {
                                $values[] = 'NULL';
                            } else {
                                $values[] = "'" . addslashes($value) . "'";
                            }
                        }
                        
                        $columns = implode('`, `', array_keys((array)$row));
                        $valuesStr = implode(', ', $values);
                        $sql .= "INSERT INTO `{$tableName}` (`{$columns}`) VALUES ({$valuesStr});\n";
                    }
                    
                    $sql .= "\n";
                }
            }

            $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

            // Write to file
            file_put_contents($path, $sql);

            if (!file_exists($path) || filesize($path) === 0) {
                throw new \Exception('Failed to create backup file');
            }

            // Create metadata file
            $metadata = [
                'filename' => $filename,
                'created_at' => Carbon::now()->toDateTimeString(),
                'size' => filesize($path),
                'tables' => count($tables),
                'created_by' => auth()->user()->name,
                'method' => 'PHP',
            ];

            file_put_contents(
                storage_path('app/backups/' . str_replace('.sql', '.json', $filename)),
                json_encode($metadata, JSON_PRETTY_PRINT)
            );

            // Log activity
            \App\Models\ActivityLog::create([
                'user_id' => auth()->id(),
                'type' => 'database_backup',
                'description' => 'Created database backup: ' . $filename,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'data' => json_encode(['filename' => $filename, 'size' => filesize($path)]),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Database backup created successfully',
                'filename' => $filename,
                'size' => $this->formatBytes(filesize($path)),
            ]);

        } catch (\Exception $e) {
            Log::error('PHP backup failed', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    /**
     * Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= (1 << (10 * $pow));
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
