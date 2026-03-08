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
        Schema::table('promotion_applications', function (Blueprint $table) {
            if (!Schema::hasColumn('promotion_applications', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            }

            if (!Schema::hasColumn('promotion_applications', 'reference_no')) {
                $table->string('reference_no', 30)->nullable()->unique()->after('user_id');
            }

            if (!Schema::hasColumn('promotion_applications', 'status')) {
                $table->string('status', 30)->default('DRAF')->after('reference_no');
            }

            if (!Schema::hasColumn('promotion_applications', 'submitted_at')) {
                $table->timestamp('submitted_at')->nullable()->after('status');
            }
        });

        // Langkah backfill wajib sebelum letak foreign key jika ada rekod lama
        DB::table('promotion_applications')
            ->whereNull('user_id')
            ->update(['user_id' => 1]); // pastikan user id 1 wujud (admin/seed)

        Schema::table('promotion_applications', function (Blueprint $table) {
            // baru enforce
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
