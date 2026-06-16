<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class BackupController extends Controller
{
    private string $disk = 'local';
    private string $dir  = 'backups';

    public function index()
    {
        $title   = 'Backup Database';
        $files   = [];
        $rawSize = 0;
        $backups = Storage::disk($this->disk)->files($this->dir);

        foreach (array_reverse($backups) as $path) {
            $name    = basename($path);
            $size    = Storage::disk($this->disk)->size($path);
            $rawSize += $size;
            $lastMod = Storage::disk($this->disk)->lastModified($path);
            $files[] = [
                'name'      => $name,
                'size'      => $this->formatBytes($size),
                'time'      => date('d M Y H:i', $lastMod),
                'timestamp' => $lastMod,
            ];
        }

        usort($files, fn($a, $b) => $b['timestamp'] - $a['timestamp']);
        $totalSize = $this->formatBytes($rawSize);

        return view('admin.backup.index', compact('title', 'files', 'totalSize'));
    }

    public function run()
    {
        try {
            Artisan::call('backup:database');
            $output = Artisan::output();
            ActivityLog::record('backup_database', 'Manual backup database dipicu oleh admin.');
            return response()->json(['success' => true, 'message' => 'Backup berhasil dibuat.', 'output' => trim($output)]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Backup gagal: ' . $e->getMessage()], 500);
        }
    }

    public function download(string $filename)
    {
        $path = $this->dir . '/' . $filename;

        if (!Storage::disk($this->disk)->exists($path)) {
            abort(404, 'File backup tidak ditemukan.');
        }

        return Storage::disk($this->disk)->download($path);
    }

    public function delete(Request $request)
    {
        $path = $this->dir . '/' . $request->filename;

        if (Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
            ActivityLog::record('hapus_backup', 'Menghapus file backup: ' . $request->filename);
            return response()->json(['success' => true, 'message' => 'File backup dihapus.']);
        }

        return response()->json(['success' => false, 'message' => 'File tidak ditemukan.']);
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
