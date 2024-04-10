<?php

namespace App\Livewire\Panel;

use Livewire\Component;

class Index extends Component
{
    public $title = 'Panel - Home';

    public function render()
    {
        return view('livewire.panel.index')->layout('layouts.dashboard-layout', [
            'title' => $this->title,
        ]);
    }
}
