<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;

class ReferenceService
{
    public static function nextPromotionRef(): string
    {
        $key = 'ETL-' . now()->format('Y');

        $next = DB::transaction(function () use ($key) {
            $row = DB::table('reference_sequences')
                ->where('key', $key)
                ->lockForUpdate()
                ->first();

            if (!$row) {
                DB::table('reference_sequences')->insert([
                    'key' => $key,
                    'current' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return 1;
            }

            $new = $row->current + 1;

            DB::table('reference_sequences')
                ->where('key', $key)
                ->update(['current' => $new, 'updated_at' => now()]);

            return $new;
        });

        return sprintf('%s-%06d', $key, $next);
    }
}
