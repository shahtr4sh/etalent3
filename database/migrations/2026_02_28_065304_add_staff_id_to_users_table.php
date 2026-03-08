<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Letak selepas email untuk kemas (optional)
            $table->string('staff_id', 30)->nullable()->after('email');
            $table->index('staff_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['staff_id']);
            $table->dropColumn('staff_id');
        });
    }
};
