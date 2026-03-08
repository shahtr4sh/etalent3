<?php

namespace App\Http\Controllers;

use App\Models\Pemohon;
use App\Models\PenyeliaanStaf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CvController extends Controller
{
    public function generate($staff_id = null)
    {
        // Log untuk debugging
        Log::info('CV Generate started', [
            'staff_id_param' => $staff_id,
            'admin_check' => Auth::guard('admin')->check(),
            'user_check' => Auth::check()
        ]);

        // Tentukan userId
        if ($staff_id) {
            // Admin access
            if (Auth::guard('admin')->check()) {
                $userId = $staff_id;
                Log::info('Admin accessing CV', ['staff_id' => $userId]);
            } else {
                Log::warning('Non-admin tried to access staff CV');
                abort(403, 'Unauthorized access');
            }
        } else {
            // Staff access own CV
            $userId = Auth::user()->staff_id ?? null;
            Log::info('Staff accessing own CV', ['user_id' => $userId]);

            if (!$userId) {
                Log::error('No staff_id found for authenticated user');
                abort(404, 'Staff not found');
            }
        }

        // Cari staff
        $staff = Pemohon::with([
            'gelaran',
            'jabatanStaf',
            'jawatanStafTerkini',
            'akademikStaf' => function($q) {
                $q->orderBy('tahun_tamat', 'desc');
            },
            'penerbitan' => function($q) {
                $q->with(['authors', 'indexes'])
                    ->orderBy('publish_date', 'desc');
            },
        ])->where('staff_id', $userId)->first();

        // CHECK: Staff dijumpai atau tidak
        if (!$staff) {
            Log::error('Staff not found in database', ['staff_id' => $userId]);

            // Return error view instead of PDF
            return response()->view('errors.staff-not-found', [
                'staff_id' => $userId
            ], 404);
        }

        Log::info('Staff found', [
            'nama' => $staff->nama,
            'staff_id' => $staff->staff_id
        ]);

        // Get supervisions
        $supervisions = PenyeliaanStaf::where(function($q) use ($userId) {
            $q->where('penyelia_utama', $userId)
                ->orWhere('penyelia_bersama', 'LIKE', "%{$userId}%");
        })
            ->with('program')
            ->orderBy('idtesis', 'desc')
            ->get();

        // Group supervisions
        $phdSupervisions = $supervisions->filter(function($s) {
            return str_contains($s->program?->namaprog_bm ?? '', 'DOKTOR FALSAFAH');
        });

        $masterSupervisions = $supervisions->filter(function($s) {
            return str_contains($s->program?->namaprog_bm ?? '', 'SARJANA');
        });

        $degreeSupervisions = $supervisions->filter(function($s) {
            return !str_contains($s->program?->namaprog_bm ?? '', 'DOKTOR') &&
                !str_contains($s->program?->namaprog_bm ?? '', 'SARJANA');
        });

        // Process profile image
        $profileImage = null;
        if ($staff->gambar_profil) {
            $imagePath = storage_path('app/public/' . $staff->gambar_profil);
            if (file_exists($imagePath)) {
                $imageData = file_get_contents($imagePath);
                $profileImage = 'data:image/jpeg;base64,' . base64_encode($imageData);
            }
        }

        $data = [
            'staff' => $staff,
            'profileImage' => $profileImage,
            'phdSupervisions' => $phdSupervisions,
            'masterSupervisions' => $masterSupervisions,
            'degreeSupervisions' => $degreeSupervisions,
        ];

        // Double-check data before sending to view
        Log::info('Sending data to PDF view', [
            'staff_exists' => !is_null($data['staff']),
            'staff_nama' => $data['staff']?->nama
        ]);

        $pdf = Pdf::loadView('pdf.cv', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'CV_' . str_replace(' ', '_', $staff->nama) . '.pdf';
        return $pdf->stream($filename);
    }
}
