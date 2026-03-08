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
    public $staff_id;
    public $is_admin_view = false;
    public $searchPenerbitan = '';

    protected $paginationTheme = 'tailwind';

    public function mount($staff_id = null, $is_admin_view = false)
    {
        $this->is_admin_view = $is_admin_view;

        if ($this->is_admin_view && $staff_id) {
            $userId = $staff_id;
        } else {
            // Kalau staff login sendiri, guna ID dia
            $userId = auth()->user()->staff_id ?? null;
        }

        if (!$userId) {
            return;
        }

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
        ])->where('staff_id', $userId)->first();

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
        // Get publications data
        $penerbitan = collect([]);
        $totalPenerbitan = 0;

        if ($this->pemohon) {
            $pubIds = PubAuthor::where('nostaf', $this->pemohon->staff_id)
                ->pluck('pub_item_id')
                ->unique();

            $totalPenerbitan = PenerbitanStaf::whereIn('id', $pubIds)->count();

            $penerbitan = PenerbitanStaf::whereIn('id', $pubIds)
                ->with(['authors', 'indexes'])
                ->orderBy('publish_date', 'desc')
                ->take(5)
                ->get();
        }

        return view('livewire.app.profile.show-profile', [
            'pemohon' => $this->pemohon,
            'penyeliaan' => $this->penyeliaan,
            'penerbitan' => $penerbitan,
            'totalPenerbitan' => $totalPenerbitan
        ]);
    }
}
