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
        Schema::create('reference_sequences', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // contoh: ETL-2026
            $table->unsignedInteger('current')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reference_sequences');
    }
};
