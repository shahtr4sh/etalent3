<?php
// routes/console.php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

// Clean expired sessions daily at midnight
Schedule::call(function () {
    // Delete sessions older than 7 days
    $deleted = DB::table('sessions')
        ->where('last_activity', '<', now()->subDays(7)->timestamp)
        ->delete();

    logger()->info("Cleaned {$deleted} expired sessions");
})->daily()->name('session-cleanup');
