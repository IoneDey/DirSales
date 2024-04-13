<?php

namespace App\Livewire\Main;

use Livewire\Component;

class Index extends Component
{
    public $title = 'Main - Home';

    public function render()
    {
        return view('livewire.main.index')->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
