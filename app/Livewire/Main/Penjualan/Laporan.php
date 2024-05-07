<?php

namespace App\Livewire\Main\Penjualan;

use App\Models\Penjualanhd;
use Livewire\Component;

class Laporan extends Component {
    public $title = 'Laporan Penjualan';
    public $tglAwal;
    public $tglAkhir;

    public $dbPenjualanhds;

    public function mount() {
        $this->tglAwal = date('Y-m-01'); // Mengambil tanggal pertama dari bulan ini
        $this->tglAkhir = date('Y-m-t'); // Mengambil tanggal terakhir dari bulan ini
    }

    public function refresh() {
        $this->dbPenjualanhds = Penjualanhd::withSum('joinPenjualandt', 'jumlah')
            ->whereBetween('tgljual', [$this->tglAwal, $this->tglAkhir])->get();
    }

    public function render() {
        //$dbPenjualanhds = Penjualanhd::whereBetween('tgljual', [$this->tglAwal, $this->tglAkhir])->get();
        $this->refresh();

        return view('livewire.main.penjualan.laporan', [
            'penjualanhds' => $this->dbPenjualanhds,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
