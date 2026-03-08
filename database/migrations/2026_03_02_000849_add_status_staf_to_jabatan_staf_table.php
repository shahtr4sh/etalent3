<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('jabatan_staf', function (Blueprint $table) {
            $table->char('status_staf', 1)->default('A')->after('kod_unit');
            $table->index('status_staf');
        });
    }

    public function down(): void
    {
        Schema::table('jabatan_staf', function (Blueprint $table) {
            $table->dropIndex(['status_staf']);
            $table->dropColumn('status_staf');
        });
    }
};
