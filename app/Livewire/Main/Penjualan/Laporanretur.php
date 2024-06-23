<?php

namespace App\Livewire\Main\Penjualan;

use App\Models\Timsetup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Laporanretur extends Component {
    use WithPagination;

    public $title = 'Laporan Retur Penjualan';
    public $tglAwal;
    public $tglAkhir;
    public $timsetupid = 'Semua';
    public $dbTimsetups;

    //--cari + paginate
    public $cari = '';
    protected $paginationTheme = 'bootstrap';
    public function paginationView() {
        return 'vendor.livewire.bootstrap';
    }
    public function updatedcari() {
        $this->resetPage();
    }
    //--end cari + paginate

    public function mount() {
        $this->tglAwal = date('Y-m-01'); // Mengambil tanggal pertama dari bulan ini
        $this->tglAkhir = date('Y-m-t'); // Mengambil tanggal terakhir dari bulan ini
        $this->dbTimsetups = Timsetup::get();
    }

    public function updatedtglAwal() {
        $this->refresh();
    }

    public function updatedtglAkhir() {
        $this->refresh();
    }

    public function refresh() {

        $startDate = Carbon::parse($this->tglAwal)->format('Y-m-d');
        $endDate = Carbon::parse($this->tglAkhir)->format('Y-m-d');

        $query = DB::table('penjualanrets as a')
            ->leftJoin('timsetups as b', 'a.timsetupid', '=', 'b.id')
            ->leftJoin('tims as c', 'b.timid', '=', 'c.id')
            ->leftJoin('penjualanhds as d', function ($join) {
                $join->on('a.timsetupid', '=', 'd.timsetupid')
                    ->on('a.nota', '=', 'd.nota');
            })
            ->select(
                'a.tglretur',
                'c.nama as tim',
                'a.nota',
                'd.customernama',
                'a.noretur',
                DB::raw('SUM(a.qty * a.harga) as totalretur')
            )
            ->whereBetween('tglretur', [$startDate, $endDate])
            ->where(function ($query) {
                $query->where('a.nota', 'like', '%' . $this->cari . '%')
                    ->orWhere('d.customernama', 'like', '%' . $this->cari . '%');
            })
            ->groupBy('a.tglretur', 'c.nama', 'a.nota', 'd.customernama', 'a.noretur');

        if ($this->timsetupid != 'Semua') {
            $query->where('a.timsetupid', $this->timsetupid);
        }

        $dbReturPenjualan = $query->paginate(25);
        return $dbReturPenjualan;
    }

    public function render() {
        $penjualanreturs = $this->refresh();

        return view('livewire.main.penjualan.laporanretur', [
            'penjualanreturs' => $penjualanreturs,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
