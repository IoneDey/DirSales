<?php

namespace App\Livewire\Main\Penagihan;

use App\Exports\Penagihan;
use App\Models\Timsetup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Laporan extends Component {
    use WithPagination;

    public $title = 'Laporan Penagihan';
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

        $query = DB::table('penagihans as a')
            ->leftJoin('penjualanhds as b', function ($join) {
                $join->on('a.nota', '=', 'b.nota')
                    ->on('a.timsetupid', '=', 'b.timsetupid');
            })
            ->leftJoin('timsetups as c', 'b.timsetupid', '=', 'c.id')
            ->leftJoin('tims as d', 'c.timid', '=', 'd.id')
            ->leftJoin('users as e', 'a.userid', '=', 'e.id')
            ->select(
                'a.id',
                'a.timsetupid',
                'd.nama as tim',
                'a.created_at',
                'a.nota',
                'b.customernama',
                'a.tglpenagihan',
                'a.namapenagih',
                'a.fotokwitansi',
                'a.jumlahbayar',
                'a.biayakomisi',
                'a.biayaadmin',
                DB::raw('a.jumlahbayar + a.biayakomisi + a.biayaadmin as total'),
                'e.name',
            )
            ->whereBetween('tglpenagihan', [$startDate, $endDate])
            ->where(function ($query) {
                $query->where('a.nota', 'like', '%' . $this->cari . '%')
                    ->orWhere('b.customernama', 'like', '%' . $this->cari . '%');
            });

        if ($this->timsetupid != 'Semua') {
            $query->where('a.timsetupid', $this->timsetupid);
        }

        $dbPenagihans = $query->paginate(25);
        return $dbPenagihans;
    }

    public function exportExcel() {
        $startDate = Carbon::parse($this->tglAwal)->format('Y-m-d');
        $endDate = Carbon::parse($this->tglAkhir)->format('Y-m-d');

        $query = DB::table('penagihans as a')
            ->leftJoin('penjualanhds as b', function ($join) {
                $join->on('a.nota', '=', 'b.nota')
                    ->on('a.timsetupid', '=', 'b.timsetupid');
            })
            ->leftJoin('timsetups as c', 'b.timsetupid', '=', 'c.id')
            ->leftJoin('tims as d', 'c.timid', '=', 'd.id')
            ->select(
                'd.nama as tim',
                'a.created_at',
                'a.nota',
                'b.customernama',
                'a.tglpenagihan',
                'a.namapenagih',
                DB::raw("CONCAT('" . asset('storage/') . "/',a.fotokwitansi) as fotokwitansi"),
                'a.jumlahbayar',
                'a.biayakomisi',
                'a.biayaadmin',
                DB::raw('a.jumlahbayar + a.biayakomisi + a.biayaadmin as total')
            )
            ->whereBetween('tglpenagihan', [$startDate, $endDate])
            ->orderBy('d.nama', 'asc')
            ->orderBy('a.tglpenagihan', 'asc');

        if ($this->timsetupid != 'Semua') {
            $query->where('a.timsetupid', $this->timsetupid);
        }

        $data = $query->get();
        return Excel::download(new Penagihan($data), 'Penagihan.xlsx');
    }

    public function render() {
        $penagihans = $this->refresh();

        $startDate = Carbon::parse($this->tglAwal)->format('Y-m-d');
        $endDate = Carbon::parse($this->tglAkhir)->format('Y-m-d');
        $penagihantTotal = DB::table('penagihans as a')
            ->leftJoin('penjualanhds as b', function ($join) {
                $join->on('a.nota', '=', 'b.nota')
                    ->on('a.timsetupid', '=', 'b.timsetupid');
            })
            ->leftJoin('timsetups as c', 'b.timsetupid', '=', 'c.id')
            ->leftJoin('tims as d', 'c.timid', '=', 'd.id')
            ->select(
                DB::raw('sum(a.jumlahbayar + a.biayakomisi + a.biayaadmin) as total')
            )
            ->whereBetween('tglpenagihan', [$startDate, $endDate])
            ->where(function ($query) {
                $query->where('a.nota', 'like', '%' . $this->cari . '%')
                    ->orWhere('b.customernama', 'like', '%' . $this->cari . '%');
            });
        if ($this->timsetupid != 'Semua') {
            $penagihantTotal->where('a.timsetupid', $this->timsetupid);
        }

        return view('livewire.main.penagihan.laporan', [
            'penagihans' => $penagihans,
            'penagihanTotal' => $penagihantTotal->first(),
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
