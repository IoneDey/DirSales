<?php

namespace App\Livewire\Panel\Pt;

use Livewire\Component;

class Index extends Component
{
    public $title = 'Panel - PT';

    public function render()
    {
        return view('livewire.panel.pt.index')->layout('layouts.panel-layout', [
            'title' => $this->title,
        ]);
    }
}
