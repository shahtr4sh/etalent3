<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CvController;
use App\Livewire\App\Profile\ShowProfile;

/*
|--------------------------------------------------------------------------
| APP MODULE (Public Profile - NO LOGIN REQUIRED)
|--------------------------------------------------------------------------
*/

Route::prefix('app')->name('app.')->group(function () {

    // PUBLIC PROFILE - anyone can view staff profile
    Route::get('/profil/{staff_id}', ShowProfile::class)
        ->name('profil.show');  // app.profil.show

    // CV route - also public (for viewing/downloading)
    Route::get('/profil/{staff_id}/cv', [CvController::class, 'generate'])
        ->name('profil.cv');
});

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES (Filament)
|--------------------------------------------------------------------------
*/

// Biarkan Filament urus sendiri /admin
//Route::get('/admin', function () {
//    return redirect('/admin/login');
//});

/*->name('login');*/
