<?php

namespace App\Livewire\Main\Penjualan;

use App\Models\Penjualanhd;
use Livewire\Component;

class Validasi extends Component {

    public $statusUser;
    public $title;
    public $whereValid;


    public $dbPenjualanhds;

    public function mount() {
        // $this->tglAwal = date('Y-m-01'); // Mengambil tanggal pertama dari bulan ini
        // $this->tglAkhir = date('Y-m-t'); // Mengambil tanggal terakhir dari bulan ini
        $this->statusUser = auth()->user()->roles;
        $this->title = 'Validasi Penjualan (' . $this->statusUser . ')';
        if (auth()->user()->roles == 'SUPERVISOR') {
            $this->whereValid = 0;
        }
        if ((auth()->user()->roles == 'SPV LOCK') || (auth()->user()->roles == 'LOCK')) {
            $this->whereValid = 'Entry Valid';
        }
        if (auth()->user()->roles == 'SPV ADMIN') {
            $this->whereValid = 'Lock Valid';
        }
    }

    public function refresh() {
        $this->dbPenjualanhds = Penjualanhd::withSum('joinPenjualandt', 'jumlah')
            ->where('status', $this->whereValid)
            ->whereOr('0', $this->whereValid)
            ->get();
    }

    public function render() {
        $this->refresh();

        return view('livewire.main.penjualan.validasi', [
            'penjualanhds' => $this->dbPenjualanhds,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
