<?php

namespace App\Livewire\App\Dashboard;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.app.dashboard.index')
            ->layout('layouts.app');
    }
}
