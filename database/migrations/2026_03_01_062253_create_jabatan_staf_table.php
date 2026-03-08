<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jabatan_staf', function (Blueprint $table) {
            $table->id();

            $table->string('no_staf', 50);
            $table->string('kod_jabatan', 50);
            $table->string('nama_jabatan', 255)->nullable();
            $table->string('kod_unit', 50)->nullable();

            $table->timestamps();

            $table->index(['no_staf']);
            $table->index(['kod_jabatan']);
            $table->index(['kod_unit']);

            // Optional: kalau mahu elak duplicate row
            $table->unique(['no_staf', 'kod_jabatan', 'kod_unit'], 'uniq_staf_jab_unit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jabatan_staf');
    }
};
