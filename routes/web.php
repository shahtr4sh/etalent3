<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\App\Auth\LoginController;
use App\Livewire\App\Profile\ShowProfile;
use App\Http\Controllers\CvController;
use App\Livewire\App\Permohonan\Index as PermohonanIndex;
use App\Livewire\App\Permohonan\Show as PermohonanShow;
use App\Livewire\App\Permohonan\Create as PermohonanCreate;
use App\Livewire\App\Dashboard\Index as DashboardIndex;

/*
|--------------------------------------------------------------------------
| APP MODULE (Pemohon)
|--------------------------------------------------------------------------
*/

Route::prefix('app')->name('app.')->group(function () {

    // Root /app: auto redirect ikut login status
    Route::get('/', function () {
        return auth()->check()
            ? redirect()->route('app.dashboard')
            : redirect()->route('app.login');
    })->name('home');

    // Login APP
    Route::get('/login', [LoginController::class, 'show'])
        ->middleware('guest')
        ->name('login');

    Route::post('/login', [LoginController::class, 'authenticate'])
        ->middleware('guest')
        ->name('login.submit');

    Route::post('/logout', [LoginController::class, 'logout'])
        ->middleware('auth')
        ->name('logout');

    // Protected routes (require login)
    Route::middleware(['auth'])->group(function () {

        Route::get('/dashboard', DashboardIndex::class)->name('dashboard');
        Route::get('/profil', ShowProfile::class)->name('profil');

        // CV route untuk staff (tanpa parameter - guna ID sendiri)
        Route::get('/profil/cv', [CvController::class, 'generate'])
            ->name('profil.cv');

        Route::get('/permohonan', PermohonanIndex::class)->name('permohonan.index');
        Route::get('/permohonan/{id}', PermohonanShow::class)
            ->whereNumber('id')
            ->name('permohonan.show');
        Route::get('/permohonan/baru', PermohonanCreate::class)->name('permohonan.create');
    });


});


/*
|--------------------------------------------------------------------------
| ADMIN (Filament)
|--------------------------------------------------------------------------
*/

Route::middleware(['web', 'auth'])->prefix('admin')->name('admin.')->group(function () {
    // Admin CV route dengan parameter staff_id
    Route::get('/staff/{staff_id}/cv', [CvController::class, 'generate'])
        ->name('staff.cv'); // akan jadi 'admin.staff.cv'
});

// Biarkan Filament urus sendiri /admin
Route::get('/login', function () {
    return redirect('/admin/login');
})->name('login');

Route::view('/test', 'layouts.test');


