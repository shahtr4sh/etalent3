<?php

namespace App\Http\Controllers;

use App\Models\Pemohon;
use App\Models\PenyeliaanStaf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CvController extends Controller
{
    public function generate($staff_id)
    {
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
        ])->where('staff_id', $staff_id)->first();

        if (!$staff) {
            abort(404, 'Staff record not found');
        }

        // Get supervisions
        $supervisions = PenyeliaanStaf::where(function($q) use ($staff_id) {
            $q->where('penyelia_utama', $staff_id)
                ->orWhere('penyelia_bersama', 'LIKE', "%{$staff_id}%");
        })
            ->with('program')
            ->orderBy('idtesis', 'desc')
            ->get();

        // Group supervisions
        $phdSupervisions = $supervisions->filter(function($s) {
            return str_contains($s->program?->namaprog_bm ?? '', 'PhD');
        });

        $masterSupervisions = $supervisions->filter(function($s) {
            return str_contains($s->program?->namaprog_bm ?? '', 'Bachelor');
        });

        $degreeSupervisions = $supervisions->filter(function($s) {
            return !str_contains($s->program?->namaprog_bm ?? '', 'PhD') &&
                !str_contains($s->program?->namaprog_bm ?? '', 'Bachelor');
        });

        // Process profile picture
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

        $pdf = Pdf::loadView('pdf.cv', $data);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'CV_' . str_replace(' ', '_', $staff->nama) . '.pdf';
        return $pdf->stream($filename);
    }
}
