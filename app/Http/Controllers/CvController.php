<?php

namespace App\Http\Controllers;

use App\Models\Pemohon;
use App\Models\PenyeliaanStaf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class CvController extends Controller
{
    public function generate()
    {
        $staffId = Auth::user()->staff_id;

        $staff = Pemohon::with([
            'gelaran',
            'jabatanStaf',
            'jawatanStafTerkini',
            'akademikStaf' => function ($q) {
                $q->orderBy('tahun_tamat', 'desc');
            },
            'penerbitan' => function ($q) {
                $q->with('authors')
                    ->orderBy('publish_date', 'desc');
            },
        ])->where('staff_id', $staffId)->first();

        // Get supervisions
        $supervisions = PenyeliaanStaf::where(function ($q) use ($staffId) {
            $q->where('penyelia_utama', $staffId)
                ->orWhere('penyelia_bersama', 'LIKE', "%{$staffId}%");
        })
            ->with('program')
            ->orderBy('idtesis', 'desc')
            ->get();

        // Process profile image
        $profileImage = storage_path('app/public/profile-pictures/usericon.jpg');
        if ($staff && $staff->gambar_profil) {
            $imagePath = storage_path('app/public/' . $staff->gambar_profil);

            if (file_exists($imagePath)) {
                $imageData = file_get_contents($imagePath);
                // Untuk .jpg guna image/jpeg
                $profileImage = 'data:image/jpeg;base64,' . base64_encode($imageData);
            }
        }

        $phdSupervisions = $supervisions->filter(function ($s) {
            return str_contains($s->program?->namaprog_bm ?? '', 'DOKTOR FALSAFAH');
        });

        $masterSupervisions = $supervisions->filter(function ($s) {
            return str_contains($s->program?->namaprog_bm ?? '', 'SARJANA');
        });

        $degreeSupervisions = $supervisions->filter(function ($s) {
            return !str_contains($s->program?->namaprog_bm ?? '', 'DOKTOR') &&
                !str_contains($s->program?->namaprog_bm ?? '', 'SARJANA');
        });

        // Data Array
        $data = [
            'staff' => $staff,
            'profileImage' => $profileImage,
            'phdSupervisions' => $phdSupervisions,
            'masterSupervisions' => $masterSupervisions,
            'degreeSupervisions' => $degreeSupervisions,
        ];

        $pdf = Pdf::loadView('pdf.cv', $data);

        $pdf->setOptions([
            'defaultFont' => 'DejaVu Sans',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'fontDir' => storage_path('fonts/'),
        ]);
        $pdf->setPaper('A4', 'portrait');

        $filename = 'CV_' . str_replace(' ', '_', $staff->nama) . '.pdf';
        return $pdf->stream($filename);
    }
}
