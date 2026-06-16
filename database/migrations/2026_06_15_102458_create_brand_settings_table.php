<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brand_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aplikasi')->default('Laravel Presensi');
            $table->string('tagline')->default('Sistem Manajemen Kehadiran');
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('footer_text')->default('Laravel Presensi');
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('brand_settings')->insert([
            'nama_aplikasi' => 'Laravel Presensi',
            'tagline'       => 'Sistem Manajemen Kehadiran',
            'logo'          => null,
            'favicon'       => null,
            'footer_text'   => 'Laravel Presensi',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_settings');
    }
};
