<?php

namespace App\Livewire\Main\Dashboard;

use Livewire\Component;

class Penjualan extends Component {
    public $title = 'Dashboard';

    public function render() {
        return view('livewire.main.dashboard.penjualan')->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
