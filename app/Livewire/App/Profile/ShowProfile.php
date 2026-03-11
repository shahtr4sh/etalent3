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
    public $penyeliaan = [];
    public $penyeliaanUtama = [];
    public $penyeliaanBersama = [];

    // Properties untuk penerbitan ikut jenis
    public $penerbitanJournal = [];
    public $penerbitanBook = [];
    public $penerbitanChapter = [];
    public $penerbitanProceeding = [];
    public $penerbitanOther = [];
    public $totalPenerbitan = 0;

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
        $this->loadPenerbitan(); // ✅ PANGGIL METHOD BARU

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

        $list = $this->pemohon->penyeliaan_list;

        if (method_exists($list, 'load')) {
            $list->load('program');
        }

        $this->penyeliaan = $list;

        $staffId = $this->pemohon->staff_id;

        $this->penyeliaanUtama   = $list->filter(fn ($tesis) => $tesis->isPenyeliaUtama($staffId))->values();
        $this->penyeliaanBersama = $list->filter(fn ($tesis) => $tesis->isPenyeliaBersama($staffId))->values();
    }

    /**
     * Load and group publications by type
     */
    private function loadPenerbitan(): void
    {
        if (!$this->pemohon) {
            return;
        }

        $pubIds = PubAuthor::where('nostaf', $this->pemohon->staff_id)
            ->pluck('pub_item_id')
            ->unique();

        $this->totalPenerbitan = PenerbitanStaf::whereIn('id', $pubIds)->count();

        // Get all publications with relationships
        $allPublications = PenerbitanStaf::whereIn('id', $pubIds)
            ->with(['authors', 'indexes'])
            ->orderBy('publish_date', 'desc')
            ->get();

        // Group by type (using lowercase for consistency)
        $this->penerbitanJournal = $allPublications->filter(fn ($pub) =>
            strtolower($pub->type) === 'journal'
        )->values();

        $this->penerbitanBook = $allPublications->filter(fn ($pub) =>
            strtolower($pub->type) === 'book'
        )->values();

        $this->penerbitanChapter = $allPublications->filter(fn ($pub) =>
            strtolower($pub->type) === 'book_chapter' ||
            strtolower($pub->type) === 'chapter-in-book'
        )->values();

        $this->penerbitanProceeding = $allPublications->filter(fn ($pub) =>
            strtolower($pub->type) === 'proceeding'
        )->values();

        // Everything else goes to Other
        $this->penerbitanOther = $allPublications->filter(fn ($pub) =>
        !in_array(strtolower($pub->type), [
            'journal',
            'book',
            'book_chapter',
            'chapter-in-book',
            'proceeding',
        ])
        )->values();
    }

    /**
     * Get publication by type
     */
    public function getPenerbitanByType($type)
    {
        return match($type) {
            'journal' => $this->penerbitanJournal,
            'book' => $this->penerbitanBook,
            'chapter-in-book' => $this->penerbitanChapter,
            'proceeding' => $this->penerbitanProceeding,
            default => $this->penerbitanOther,
        };
    }

    public function updatingSearchPenerbitan()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.app.profile.show-profile', [
            'pemohon'          => $this->pemohon,
            'penyeliaan'       => $this->penyeliaan,
            'penyeliaanUtama'  => $this->penyeliaanUtama,
            'penyeliaanBersama'=> $this->penyeliaanBersama,
            'penerbitanJournal' => $this->penerbitanJournal,
            'penerbitanBook'    => $this->penerbitanBook,
            'penerbitanChapter' => $this->penerbitanChapter,
            'penerbitanProceeding' => $this->penerbitanProceeding,
            'penerbitanOther'   => $this->penerbitanOther,
            'totalPenerbitan'   => $this->totalPenerbitan,
        ]);
    }
}
