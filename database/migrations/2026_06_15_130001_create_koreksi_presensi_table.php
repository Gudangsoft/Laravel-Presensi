<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('koreksi_presensi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('karyawan_id');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->text('keterangan');
            $table->tinyInteger('status')->default(0); // 0=pending, 1=disetujui, 2=ditolak
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            $table->foreign('karyawan_id')->references('id')->on('karyawan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('koreksi_presensi');
    }
};
