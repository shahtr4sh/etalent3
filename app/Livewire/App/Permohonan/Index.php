<?php

namespace App\Livewire\App\Permohonan;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\PromotionApplication;

class Index extends Component
{
    public array $applications = [];

    public function mount(): void
    {
        $staffId = Auth::user()?->staff_id;

        $this->applications = PromotionApplication::query()
            ->where('staff_id', $staffId)
            ->orderByDesc('created_at')
            ->get(['id', 'reference_no', 'status', 'is_active', 'created_at'])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.app.permohonan.index')
            ->layout('layouts.app');
    }
}
