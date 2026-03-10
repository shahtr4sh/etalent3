<?php

namespace App\Livewire\App\Profile;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pemohon;
use App\Models\PubAuthor;
use App\Models\PenerbitanStaf;
use App\Models\StafMarkah;
use App\Models\StafPerformance;
use App\Models\StafTatatertib;

class ShowProfile extends Component
{
    use WithPagination;

    public $pemohon;
    public $penyeliaan = [];           // kekalkan jika masih digunakan tempat lain
    public $penyeliaanUtama = [];      // ✅ baharu
    public $penyeliaanBersama = [];    // ✅ baharu

    public $staff_id;
    public $performanceEvaluations = [];
    public $searchPenerbitan = '';
    public $semuaMarkah = [];
    public $tatatertib = [];

    protected $paginationTheme = 'tailwind';

    public function mount($staff_id)
    {
        $this->staff_id = $staff_id;

        $this->pemohon = Pemohon::with([
            'gelaran',
            'akademikStaf' => fn ($q) => $q->orderByDesc('tahun_tamat')->orderByDesc('kod_tahap'),
            'jabatanStaf',
            'jawatanStaf' => fn ($q) => $q->orderByDesc('terkini')->orderByDesc('aktif'),
            'jawatanStafTerkini',
            'markahTerkini',
        ])->where('staff_id', $this->staff_id)->first();

        if (!$this->pemohon) {
            abort(404, 'Staff tidak dijumpai');
        }

        $this->semuaMarkah = StafMarkah::where('no_staf', $this->staff_id)
            ->orderBy('tahun_markah', 'desc')
            ->get();

        $this->loadPenyeliaan();

        $this->performanceEvaluations = StafPerformance::where('no_staf', $this->staff_id)
            ->orderBy('year', 'desc')
            ->get();

        $this->tatatertib = StafTatatertib::where('no_staf', $this->staff_id)
            ->orderBy('tarikh_conduct', 'desc')
            ->get();
    }

    private function loadPenyeliaan(): void
    {
        if (!$this->pemohon) {
            return;
        }

        // Ambil semua tesis yang melibatkan staf ini (seperti sedia ada)
        $list = $this->pemohon->penyeliaan_list;

        if (method_exists($list, 'load')) {
            $list->load('program');
        }

        $this->penyeliaan = $list;

        // Asingkan jenis penyeliaan
        $staffId = $this->pemohon->staff_id;

        $this->penyeliaanUtama   = $list->filter(fn ($tesis) => $tesis->isPenyeliaUtama($staffId))->values();
        $this->penyeliaanBersama = $list->filter(fn ($tesis) => $tesis->isPenyeliaBersama($staffId))->values();
    }

    public function updatingSearchPenerbitan()
    {
        $this->resetPage();
    }

    public function render()
    {
        $penerbitan = collect([]);
        $totalPenerbitan = 0;

        if ($this->pemohon) {
            $pubIds = PubAuthor::where('nostaf', $this->pemohon->staff_id)
                ->pluck('pub_item_id')
                ->unique();

            $totalPenerbitan = PenerbitanStaf::whereIn('id', $pubIds)->count();

            $penerbitan = PenerbitanStaf::whereIn('id', $pubIds)
                ->with(['authors', 'indexes'])
                ->orderBy('type', 'asc')
                ->orderBy('publish_date', 'desc')
                ->get();
        }

        return view('livewire.app.profile.show-profile', [
            'pemohon'          => $this->pemohon,
            'penyeliaan'       => $this->penyeliaan,
            'penyeliaanUtama'  => $this->penyeliaanUtama,
            'penyeliaanBersama'=> $this->penyeliaanBersama,
            'penerbitan'       => $penerbitan,
            'totalPenerbitan'  => $totalPenerbitan,
        ]);
    }
}
