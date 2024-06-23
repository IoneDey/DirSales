<?php

namespace App\Livewire\Main\Penagihan;

use App\Models\Timsetup;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Laporanpenagihannota extends Component {
    use WithPagination;
    public $title = 'Laporan Sisa Penagihan / Nota';

    public $dbTimsetups;
    public $tim = 'Semua';

    public function mount() {
        $this->dbTimsetups = Timsetup::get();
    }

    public function refresh() {
        $tim = $this->tim;

        $query = "
            WITH ctePenjualan AS (
                SELECT
                    e.nama as Tim,
                    a.Nota,
                    a.Customernama,
                    SUM((b.jumlah + b.jumlahkoreksi) * c.hargajual) as TotalPenjualan
                FROM penjualanhds a
                LEFT JOIN penjualandts b ON a.id = b.penjualanhdid
                LEFT JOIN timsetuppakets c ON b.timsetuppaketid = c.id
                LEFT JOIN timsetups d ON a.timsetupid = d.id
                LEFT JOIN tims e ON d.timid = e.id
                GROUP BY e.nama, a.nota, a.customernama
            ), ctePenagihan AS (
                SELECT
                    c.nama as Tim,
                    a.nota,
                    SUM(jumlahbayar + biayakomisi + biayaadmin) as TotalPenagihan
                FROM penagihans a
                LEFT JOIN timsetups b ON a.timsetupid = b.id
                LEFT JOIN tims c ON b.timid = c.id
                GROUP BY c.nama, a.nota
            )
            SELECT
                a.*, IFNULL(b.TotalPenagihan, 0) as TotalPenagihan,
                a.TotalPenjualan - IFNULL(b.TotalPenagihan, 0) as Sisa
            FROM ctePenjualan a
            LEFT JOIN ctePenagihan b ON a.Tim = b.Tim AND a.nota = b.nota
        ";

        if ($tim != 'Semua') {
            $query .= " WHERE a.Tim = :tim";
        }

        $query .= " ORDER BY a.Tim, Sisa DESC";

        $salesData = DB::select($query, $tim != 'Semua' ? ['tim' => $tim] : []);

        // Mengelompokkan hasil berdasarkan 'Tim' dan mengurutkan berdasarkan 'Sisa' secara menurun dalam setiap kelompok
        $groupedData = collect($salesData)->groupBy('Tim')->map(function ($group) {
            return $group->sortByDesc('Sisa');
        });

        return collect($groupedData);
    }

    public function render() {
        $salesData = $this->refresh();

        return view('livewire.main.penagihan.laporanpenagihannota', [
            'salesData' => $salesData,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
