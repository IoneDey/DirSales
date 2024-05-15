<?php

namespace App\Livewire\Main\Penjualan;

use App\Models\Penjualanhd;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Laporan extends Component {
    public $title = 'Laporan Penjualan';
    public $tglAwal;
    public $tglAkhir;
    public $isSpreadsheet;

    public $status = true;
    public $dbPenjualanhds;
    public $nota;

    public $btnDisables = false;


    public function mount() {
        $this->tglAwal = date('Y-m-01'); // Mengambil tanggal pertama dari bulan ini
        $this->tglAkhir = date('Y-m-t'); // Mengambil tanggal terakhir dari bulan ini
    }

    public function updatedtglAwal() {
        $this->refresh();
    }

    public function updatedtglAkhir() {
        $this->refresh();
    }

    public function updatedstatus() {
        $this->refresh();
    }

    public function confirmUploadToSpreadsheet($nota) {
        $this->nota = $nota;
        $this->btnDisables = true;
    }

    public function cancleUploadToSpreadsheet() {
        $this->btnDisables = false;
    }

    public function uploadToSpreadsheet() {
        // $results = DB::table('penjualanhds as a')
        //     ->leftJoin('penjualandts as b', 'b.penjualanhdid', '=', 'a.id')
        //     ->leftJoin('timsetuppakets as c', 'c.id', '=', 'b.timsetuppaketid')
        //     ->leftJoin('timsetupbarangs as d', 'd.timsetuppaketid', '=', 'c.id')
        //     ->leftJoin('barangs as e', 'e.id', '=', 'd.barangid')
        //     ->select(
        //         'a.created_at',
        //         'a.tgljual',
        //         'a.customernama',
        //         'e.nama as namabarang',
        //         DB::raw('b.jumlah + b.jumlahkoreksi as jumlah'),
        //         'a.customernotelp',
        //         'a.nota',
        //         'a.namasales',
        //         'a.customeralamat',
        //         'a.fotonota',
        //         DB::raw("'' as tglbayar"),
        //         DB::raw("'' as angsuran1"),
        //         DB::raw("0 as totalbayar")
        //     )
        //     ->where('a.nota', '=', $this->nota)
        //     ->get();

        $results = DB::table('penjualanhds as a')
            ->leftJoin('penjualandts as b', 'b.penjualanhdid', '=', 'a.id')
            ->leftJoin('timsetuppakets as c', 'c.id', '=', 'b.timsetuppaketid')
            ->leftJoin('timsetupbarangs as d', 'd.timsetuppaketid', '=', 'c.id')
            ->leftJoin('barangs as e', 'e.id', '=', 'd.barangid')
            ->leftJoin('timsetups as f', 'a.timsetupid', '=', 'f.id')
            ->leftJoin('kotas as g', 'f.kotaid', '=', 'g.id')
            ->select(
                'a.created_at',
                'a.tgljual',
                'a.customernama',
                DB::raw("CONCAT(c.nama, ':', e.nama) AS namabarang"),
                DB::raw('(b.jumlah + b.jumlahkoreksi) AS jumlah'),
                'a.customernotelp',
                'a.nota',
                'a.namasales',
                'a.customeralamat',
                'a.fotonota',
                'a.fotonotarekap',
                DB::raw("'' AS kecamatan"),
                'g.nama AS kota',
                DB::raw('(b.jumlah + b.jumlahkoreksi) * c.hargajual AS omset'),
                DB::raw('(b.jumlah + b.jumlahkoreksi) * d.hpp AS hpp')
            )
            ->where('a.nota', '=', $this->nota)
            ->get();

        $dataArr = [];
        foreach ($results as $item) {
            $dataArr[] = [
                'created_at' => $item->created_at,
                'tgljual' => $item->tgljual,
                'customernama' => $item->customernama,
                'namabarang' => $item->namabarang,
                'jumlah' => $item->jumlah,
                'customernotelp' => $item->customernotelp,
                'nota' => $item->nota,
                'namasales' => $item->namasales,
                'customeralamat' => $item->customeralamat,
                'fotonota' => $item->fotonota,
                'fotonotarekap' => $item->fotonotarekap,
                'kecamatan' => $item->kecamatan,
                'kota' => $item->kota,
                'omset' => $item->omset,
                'hpp' => $item->hpp,
            ];
        }
        $jsonData = json_encode($dataArr);

        try {
            $client = new Client();
            $response = $client->post('https://script.google.com/macros/s/AKfycbxgwjS6ESiZoufN4g--mdLV9h08Nvjc2o0bliDY_xzpDjjdiPs5Fm-MffNVLngEd9G3og/exec', [
                'json' => $jsonData
            ]);
        } catch (RequestException $e) {
            // session()->flash('ok', 'Kesalahan permintaan HTTP: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        } catch (\Exception $e) {
            session()->flash('ok', 'Kesalahan umum: ' . $e->getMessage());
            // return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }

        $body = $response->getBody()->getContents();
        session()->flash('ok', $body);
        $this->btnDisables = false;

        try {
            Penjualanhd::updateOrCreate(['nota' => $this->nota], ['sheet' => 1]);
        } catch (\Exception $e) {
            // dd($e->getMessage());
        }
    }

    public function refresh() {
        $startDate = Carbon::parse($this->tglAwal)->format('Y-m-d');
        $endDate = Carbon::parse($this->tglAkhir)->format('Y-m-d');

        $this->dbPenjualanhds = Penjualanhd::withSum('joinPenjualandt', DB::raw('jumlah + jumlahkoreksi'))
            ->whereBetween('tgljual', [$startDate, $endDate])
            ->where(($this->status == 1 ? DB::raw(true) : 'status'), $this->status)
            ->get();
    }

    public function render() {
        $this->refresh();

        $gTotalJual = Penjualanhd::selectRaw('sum((b.jumlah+b.jumlahkoreksi)*c.hargajual) as totaljual')
            ->leftJoin('penjualandts as b', 'penjualanhds.id', '=', 'b.penjualanhdid')
            ->leftJoin('timsetuppakets as c', 'c.id', '=', 'b.timsetuppaketid')
            ->whereBetween('tgljual', [$this->tglAwal, $this->tglAkhir])
            ->where(($this->status == 1 ? DB::raw(true) : 'status'), $this->status)
            ->first();

        return view('livewire.main.penjualan.laporan', [
            'penjualanhds' => $this->dbPenjualanhds,
            'grandTotal' => $gTotalJual,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
