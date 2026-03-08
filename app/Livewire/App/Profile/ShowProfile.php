<?php

namespace App\Livewire\App\Profile;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Pemohon;
use App\Models\PenyeliaanStaf;
use App\Models\PubAuthor;
use App\Models\PenerbitanStaf;

class ShowProfile extends Component
{
    use WithPagination;

    public $pemohon;
    public $penyeliaan = [];
    public $searchPenerbitan = '';

    protected $paginationTheme = 'tailwind';

    public function mount()
    {
        $user = auth()->user();

        $this->pemohon = Pemohon::with([
            'gelaran',
            'akademikStaf' => function ($q) {
                $q->orderByDesc('tahun_tamat')->orderByDesc('kod_tahap');
            },
            'jabatanStaf',
            'jawatanStaf' => function ($q) {
                $q->orderByDesc('terkini')->orderByDesc('aktif');
            },
            'jawatanStafTerkini',
            'markahTerkini',
        ])->where('staff_id', $user?->staff_id)->first();

        $this->loadPenyeliaan();
    }

    private function loadPenyeliaan()
    {
        if (!$this->pemohon) {
            return;
        }

        $this->penyeliaan = $this->pemohon->penyeliaan_list;
    }

    public function updatingSearchPenerbitan()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.app.profile.show-profile', [
            'penerbitan' => $this->pemohon->penerbitan_list ?? collect([]),
            'totalPenerbitan' => $this->pemohon->penerbitan_count ?? 0,
        ]);
    }
}
