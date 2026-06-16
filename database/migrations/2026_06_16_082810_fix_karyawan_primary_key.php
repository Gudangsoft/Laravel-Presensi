<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Rename karyawan_id → id only if needed (production schema mismatch)
        $columns = array_column(DB::select('DESCRIBE karyawan'), 'Field');

        if (!in_array('id', $columns) && in_array('karyawan_id', $columns)) {
            Schema::table('karyawan', function (Blueprint $table) {
                $table->renameColumn('karyawan_id', 'id');
            });
        }
    }

    public function down(): void
    {
        $columns = array_column(DB::select('DESCRIBE karyawan'), 'Field');

        if (in_array('id', $columns)) {
            Schema::table('karyawan', function (Blueprint $table) {
                $table->renameColumn('id', 'karyawan_id');
            });
        }
    }
};
