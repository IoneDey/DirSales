<?php

namespace App\Livewire\Panel\Kota;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.panel.kota.index')->layout('layouts.dashboard-layout');
    }
}
