<?php

namespace App\Livewire\App\Permohonan;

use Livewire\Component;

class Form extends Component
{
    public function render()
    {
        return view('livewire.permohonan.form')
            ->layout('layouts.app');
    }
}
