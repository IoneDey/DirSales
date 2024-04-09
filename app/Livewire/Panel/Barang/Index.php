<?php

namespace App\Livewire\Panel\Barang;

use Livewire\Component;

class Index extends Component
{
    public $title = 'Panel - Barang';

    public function render()
    {
        return view('livewire.panel.barang.index')->layout('layouts.panel-layout', [
            'title' => $this->title,
        ]);
    }
}
