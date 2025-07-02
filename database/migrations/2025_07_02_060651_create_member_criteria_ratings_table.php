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
        Schema::create('member_criteria_ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_member_id')->constrained()->onDelete('cascade');
            $table->foreignId('criteria_id')->constrained()->onDelete('cascade');
            $table->integer('score'); // Nilai penilaian (misalnya, 1-5 atau 1-10)
            $table->timestamps();

            // Membuat kombinasi team_member_id dan criteria_id menjadi unik
            // Ini mencegah satu anggota tim memiliki dua nilai untuk kriteria yang sama
            $table->unique(['team_member_id', 'criteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_criteria_ratings');
    }
};
