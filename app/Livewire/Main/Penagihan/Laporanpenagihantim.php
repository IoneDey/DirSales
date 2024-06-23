<?php

namespace App\Livewire\Main\Penagihan;

use Livewire\Component;

class Laporanpenagihantim extends Component {
    public $title = 'Laporan';

    public function render() {
        return view('livewire.main.penagihan.laporanpenagihantim')->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
