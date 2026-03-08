<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pemohon', function (Blueprint $table) {
            $table->string('staff_id')->unique();

            // 1-1 ke users
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->unique();

            // Profil Pemohon / Staf
            $table->string('nama');

            $table->string('gred_semasa')->nullable();     // contoh: "DS13"
            $table->string('jawatan_semasa')->nullable();  // contoh: "Pegawai"

            $table->string('ptj_fakulti')->nullable();
            $table->string('jabatan')->nullable();

            $table->string('emel_rasmi')->nullable();
            $table->string('no_telefon')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pemohon');
    }
};
