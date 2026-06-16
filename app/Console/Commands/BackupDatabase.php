<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{
    protected $signature   = 'backup:database';
    protected $description = 'Backup database menggunakan mysqldump, simpan di storage/app/backups/';

    public function handle(): int
    {
        $host     = config('database.connections.mysql.host', '127.0.0.1');
        $port     = config('database.connections.mysql.port', '3306');
        $database = config('database.connections.mysql.database');
        $username = config('database.connections.mysql.username');
        $password = config('database.connections.mysql.password');

        if (!$database) {
            $this->error('Database config tidak ditemukan.');
            return self::FAILURE;
        }

        Storage::disk('local')->makeDirectory('backups');

        $filename  = 'backup-' . now()->format('Y-m-d_His') . '.sql';
        $localPath = storage_path('app/backups/' . $filename);

        $passArg = $password ? '--password=' . escapeshellarg($password) : '';
        $cmd     = sprintf(
            'mysqldump --host=%s --port=%s --user=%s %s %s > %s 2>&1',
            escapeshellarg($host),
            escapeshellarg($port),
            escapeshellarg($username),
            $passArg,
            escapeshellarg($database),
            escapeshellarg($localPath)
        );

        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0 || !file_exists($localPath) || filesize($localPath) === 0) {
            $this->error('mysqldump gagal: ' . implode("\n", $output));
            if (file_exists($localPath)) unlink($localPath);
            return self::FAILURE;
        }

        $size = $this->formatBytes(filesize($localPath));
        $this->info("Backup berhasil: {$filename} ({$size})");

        // Pertahankan hanya 7 backup terakhir
        $files = collect(Storage::disk('local')->files('backups'))
            ->sortDesc()
            ->values();

        if ($files->count() > 7) {
            $files->slice(7)->each(fn($f) => Storage::disk('local')->delete($f));
            $this->info('Backup lama dihapus, menyimpan 7 terakhir.');
        }

        return self::SUCCESS;
    }

    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) return round($bytes / 1048576, 2) . ' MB';
        if ($bytes >= 1024)    return round($bytes / 1024, 2) . ' KB';
        return $bytes . ' B';
    }
}
