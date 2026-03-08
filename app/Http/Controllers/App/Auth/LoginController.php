<?php

namespace App\Http\Controllers\App\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pemohon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB; // Add this for database session cleanup

class LoginController extends Controller
{
    public function show()
    {
        return view('app.auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $email = strtolower(trim($request->input('email')));
        $password = $request->input('password');

        // 1) Pastikan email wujud dalam table pemohon
        $pemohon = Pemohon::query()
            ->whereRaw('LOWER(TRIM(emel_rasmi)) = ?', [$email])
            ->first();

        if (!$pemohon) {
            return back()->withErrors([
                'email' => 'Emel rasmi ini tidak wujud dalam rekod staf UniSHAMS.',
            ])->withInput();
        }

        if (blank($pemohon->staff_id)) {
            return back()->withErrors([
                'email' => 'Rekod staf dijumpai tetapi Staff ID tiada. Sila semak data pemohon.',
            ])->withInput();
        }

        // 2) Sync users
        $user = User::query()->whereRaw('LOWER(TRIM(email)) = ?', [$email])->first();

        if (!$user) {
            $user = User::create([
                'name' => $pemohon->nama,
                'email' => $email,
                'staff_id' => $pemohon->staff_id,
                'password' => Hash::make($pemohon->staff_id),
            ]);
        } else {
            $dirty = false;

            if ($user->staff_id !== $pemohon->staff_id) {
                $user->staff_id = $pemohon->staff_id;
                $dirty = true;
            }

            if (!blank($pemohon->nama) && $user->name !== $pemohon->nama) {
                $user->name = $pemohon->nama;
                $dirty = true;
            }

            if ($dirty) {
                $user->save();
            }
        }

        // 3) Verify password
        if (!Hash::check($password, $user->password)) {
            return back()->withErrors([
                'password' => 'Kata laluan tidak tepat.',
            ])->withInput();
        }

        // Check if user is logged into admin guard
        if (Auth::guard('admin')->check()) {
            // Get the admin user ID before logout
            $adminUserId = Auth::guard('admin')->id();

            // Logout from admin guard
            Auth::guard('admin')->logout();

            // If using database session driver, invalidate admin sessions
            if (config('session.driver') === 'database' && $adminUserId) {
                DB::table('sessions')
                    ->where('user_id', $adminUserId)
                    ->where('user_guard', 'admin')
                    ->delete();
            }
        }

        // =============================================
        // 🔥 NEW: Also invalidate any other app sessions for this user
        // =============================================

        if (config('session.driver') === 'database') {
            // Delete ALL previous sessions for this user in web guard
            DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('user_guard', 'web')
                ->delete();
        }

        // 4) Login user yang telah disync
        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('app.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('app.login');
    }
}
