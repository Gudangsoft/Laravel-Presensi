<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // presensi: heavily queried by karyawan_id + tanggal_presensi
        Schema::table('presensi', function (Blueprint $table) {
            if (!$this->hasIndex('presensi', 'presensi_karyawan_id_index')) {
                $table->index('karyawan_id');
            }
            if (!$this->hasIndex('presensi', 'presensi_tanggal_presensi_index')) {
                $table->index('tanggal_presensi');
            }
            if (!$this->hasIndex('presensi', 'presensi_karyawan_id_tanggal_presensi_index')) {
                $table->index(['karyawan_id', 'tanggal_presensi']);
            }
        });

        // pengajuan_presensi: queried by karyawan_id, status_approved, tanggal_pengajuan
        Schema::table('pengajuan_presensi', function (Blueprint $table) {
            if (!$this->hasIndex('pengajuan_presensi', 'pengajuan_presensi_karyawan_id_index')) {
                $table->index('karyawan_id');
            }
            if (!$this->hasIndex('pengajuan_presensi', 'pengajuan_presensi_status_approved_index')) {
                $table->index('status_approved');
            }
            if (!$this->hasIndex('pengajuan_presensi', 'pengajuan_presensi_tanggal_pengajuan_index')) {
                $table->index('tanggal_pengajuan');
            }
        });

        // notifikasi: whereNull('read_at')->count() on every page
        Schema::table('notifikasi', function (Blueprint $table) {
            if (!$this->hasIndex('notifikasi', 'notifikasi_read_at_index')) {
                $table->index('read_at');
            }
        });

        // hari_libur: queried by tanggal and is_recurring
        Schema::table('hari_libur', function (Blueprint $table) {
            if (!$this->hasIndex('hari_libur', 'hari_libur_tanggal_index')) {
                $table->index('tanggal');
            }
            if (!$this->hasIndex('hari_libur', 'hari_libur_is_recurring_index')) {
                $table->index('is_recurring');
            }
        });

        // koreksi_presensi: queried by karyawan_id
        Schema::table('koreksi_presensi', function (Blueprint $table) {
            if (!$this->hasIndex('koreksi_presensi', 'koreksi_presensi_karyawan_id_index')) {
                $table->index('karyawan_id');
            }
            if (!$this->hasIndex('koreksi_presensi', 'koreksi_presensi_status_index')) {
                $table->index('status');
            }
        });

        // activity_logs: queried by created_at
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!$this->hasIndex('activity_logs', 'activity_logs_created_at_index')) {
                $table->index('created_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('presensi', function (Blueprint $table) {
            $table->dropIndex(['karyawan_id']);
            $table->dropIndex(['tanggal_presensi']);
            $table->dropIndex(['karyawan_id', 'tanggal_presensi']);
        });
        Schema::table('pengajuan_presensi', function (Blueprint $table) {
            $table->dropIndex(['karyawan_id']);
            $table->dropIndex(['status_approved']);
            $table->dropIndex(['tanggal_pengajuan']);
        });
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropIndex(['read_at']);
        });
        Schema::table('hari_libur', function (Blueprint $table) {
            $table->dropIndex(['tanggal']);
            $table->dropIndex(['is_recurring']);
        });
        Schema::table('koreksi_presensi', function (Blueprint $table) {
            $table->dropIndex(['karyawan_id']);
            $table->dropIndex(['status']);
        });
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });
    }

    private function hasIndex(string $table, string $index): bool
    {
        $indexes = \Illuminate\Support\Facades\DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index]);
        return count($indexes) > 0;
    }
};
