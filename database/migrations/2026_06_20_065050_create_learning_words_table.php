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
        Schema::create('learning_words', function (Blueprint $table) {
            $table->id();
            $table->string('word');
            $table->string('translation');
            $table->string('phonetic')->nullable();
            $table->text('example')->nullable();
            $table->enum('level', ['easy', 'medium', 'hard'])->default('easy');
            $table->string('category')->default('general');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_words');
    }
};
