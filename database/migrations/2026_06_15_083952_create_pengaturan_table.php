<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->time('jam_masuk')->default('08:00:00');
            $table->time('jam_pulang')->default('17:00:00');
            $table->integer('toleransi')->default(0);
            $table->timestamps();
        });

        DB::table('pengaturan')->insert([
            'jam_masuk'  => '08:00:00',
            'jam_pulang' => '17:00:00',
            'toleransi'  => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan');
    }
};
