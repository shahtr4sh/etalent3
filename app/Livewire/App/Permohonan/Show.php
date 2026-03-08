<?php

namespace App\Livewire\App\Permohonan;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\PromotionApplication;

class Show extends Component
{
    public int $id;
    public ?array $application = null;

    public string $statusKey = 'TIADA_STATUS';
    public array $statusMeta = [];

    public function mount(int $id): void
    {
        $this->id = $id;
        $this->statusMeta = $this->statusMetaMap();

        $staffId = Auth::user()?->staff_id;

        $row = PromotionApplication::query()
            ->where('id', $id)
            ->where('staff_id', $staffId)
            ->first();

        $this->application = $row ? $row->toArray() : null;

        $this->statusKey = $this->application['status'] ?? 'TIADA_STATUS';
        if (!isset($this->statusMeta[$this->statusKey])) {
            $this->statusKey = 'TIADA_STATUS';
        }
    }

    private function statusMetaMap(): array
    {
        return [
            'DRAF'             => ['label' => 'Draf',             'bg' => 'bg-gray-100',   'text' => 'text-gray-800',   'border' => 'border-gray-300'],
            'DIHANTAR'         => ['label' => 'Dihantar',         'bg' => 'bg-blue-100',   'text' => 'text-blue-800',   'border' => 'border-blue-300'],
            'MENUNGGU SEMAKAN' => ['label' => 'Menunggu Semakan', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-300'],
            'DALAM SEMAKAN'    => ['label' => 'Dalam Semakan',    'bg' => 'bg-amber-100',  'text' => 'text-amber-800',  'border' => 'border-amber-300'],
            'DIPULANGKAN'      => ['label' => 'Dipulangkan',      'bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'border' => 'border-orange-300'],
            'UNTUK KELULUSAN'  => ['label' => 'Untuk Kelulusan',  'bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'border' => 'border-purple-300'],
            'PERLU MAKLUMAT'   => ['label' => 'Perlu Maklumat',   'bg' => 'bg-pink-100',   'text' => 'text-pink-800',   'border' => 'border-pink-300'],
            'TANGGUH'          => ['label' => 'Tangguh',          'bg' => 'bg-slate-100',  'text' => 'text-slate-800',  'border' => 'border-slate-300'],
            'LULUS'            => ['label' => 'Lulus',            'bg' => 'bg-green-100',  'text' => 'text-green-800',  'border' => 'border-green-300'],
            'TIDAK LULUS'      => ['label' => 'Tidak Lulus',      'bg' => 'bg-red-100',    'text' => 'text-red-800',    'border' => 'border-red-300'],
            'DITUTUP'          => ['label' => 'Ditutup',          'bg' => 'bg-zinc-100',   'text' => 'text-zinc-800',   'border' => 'border-zinc-300'],
            'TIADA_STATUS'     => ['label' => 'Tidak Diketahui',  'bg' => 'bg-gray-100',   'text' => 'text-gray-800',   'border' => 'border-gray-300'],
        ];
    }

    public function render()
    {
        return view('livewire.app.permohonan.show')
            ->layout('layouts.app');
    }
}
