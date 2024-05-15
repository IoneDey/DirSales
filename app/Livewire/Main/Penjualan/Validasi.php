<?php

namespace App\Livewire\Main\Penjualan;

use App\Http\Controllers\SendWaMessage;
use App\Models\Penjualanhd;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class Validasi extends Component {
    use WithFileUploads;

    public $statusUser;
    public $title;
    public $whereValid;

    public $dbPenjualanhds;
    public $gTotalJual;

    //test spreadsheet
    public $nota;
    public $penjualanhdid;

    public $validMessage;

    public function testSendWA() {
        $countdata = $this->dbPenjualanhds->count();
        $totalJual = $this->gTotalJual;
        $imageUrl = "https://images.tokopedia.net/img/cache/215-square/GAnVPX/2022/6/14/dc496755-0535-4dc1-9403-374e77ecdc1d.jpg";

        $message = <<<EOD
            Halo,
            Ini adalah pesan dari php.
            ada $countdata data penjualan yg baru diselesaikan admin entry,
            dengan total $totalJual.
            Harap segera ditindak lanjuti, Terima kasih.
            EOD;
        //dd($message);
        $whatsAppController = new SendWaMessage();
        //$response = $whatsAppController->sendMessage('6287701666286', $message);
        $response = $whatsAppController->sendMessageWithImage('6287701666286', $imageUrl, 'Test Kirim Gambar', 'Logo Dinasty');
        dump($response);
    }

    public function resetErrors() {
        $this->resetErrorBag();
    }

    public function cekValidasi($nota) {
        // cek kelengkapan entry data
        $validHeader = "";
        $validDetail = "";
        $this->validMessage = "";

        $cekheader = PenjualanHd::where('nota', $nota)->first();
        $this->penjualanhdid = $cekheader->id;
        $this->nota = $cekheader->nota;
        if ($cekheader->shareloc == '') {
            $validHeader .= 'Share lock belum diisi.<br>';
        }
        if ($cekheader->fotonota == '') {
            $validHeader .= 'Foto nota belum diisi.<br><br>';
        }

        // cek kelengkapan detail barang data
        $cekdetail = PenjualanHd::leftJoin('penjualandts as b', 'b.penjualanhdid', '=', 'penjualanhds.id')
            ->leftJoin('timsetuppakets as c', function ($join) {
                $join->on('c.id', '=', 'b.timsetuppaketid')
                    ->leftJoin('timsetupbarangs as d', 'd.timsetuppaketid', '=', 'c.id');
            })
            ->where('nota', $nota)
            ->where('d.timsetuppaketid', null)
            ->select('c.nama')
            ->get();

        if ($cekdetail->count() != 0) {
            $validDetail = "Paket ";
            foreach ($cekdetail as $item) {
                $validDetail .= $item->nama . ", ";
            }
            $validDetail = rtrim($validDetail, ", ") . ". Tidak memiliki detail barang.<br>";
            $validDetail .= "Cek ulang entry penjualan atau<br>";
            $validDetail .= "hubungi supervisor untuk melakukan cek setting barang pada paket.";
        }

        $this->validMessage = $validHeader . $validDetail;

        // if ($this->validMessage != "") {
        //     session()->flash('error', $this->validMessage);
        // } else {
        //     session()->flash('ok', 'data lengkap');
        // }
    }

    public function valid() {
        if ($this->penjualanhdid && $this->validMessage == '') {
            //dd('valid');
            $data = Penjualanhd::find($this->penjualanhdid);

            if (auth()->user()->roles == 'SUPERVISOR') {
                $data->status = 'Valid';
            }
            if ((auth()->user()->roles == 'LOCK')) {
                $data->userlockid = auth()->user()->id;
                $data->validatedlock_at =  now();
                $data->status = 'Lock Valid';
            }
            $data->save();
        }
    }

    public function mount() {
        $this->statusUser = auth()->user()->roles;
        $this->title = 'Validasi Penjualan (' . $this->statusUser . ')';
        if (auth()->user()->roles == 'SUPERVISOR') {
            $this->whereValid = 0;
        }
        if ((auth()->user()->roles == 'LOCK')) {
            $this->whereValid = 'Entry Valid';
        }
        // if (auth()->user()->roles == 'SPV ADMIN') {
        //     $this->whereValid = 'Lock Valid';
        // }
    }

    public function refresh() {
        $this->dbPenjualanhds = Penjualanhd::withSum('joinPenjualandt', DB::raw('jumlah + jumlahkoreksi'))
            ->where('status', $this->whereValid)
            ->where('status', '!=', 'Valid')
            ->get();
    }

    public function render() {
        $this->refresh();

        $gTotalJual = Penjualanhd::selectRaw('sum((b.jumlah+b.jumlahkoreksi)*c.hargajual) as totaljual')
            ->leftJoin('penjualandts as b', 'penjualanhds.id', '=', 'b.penjualanhdid')
            ->leftJoin('timsetuppakets as c', 'c.id', '=', 'b.timsetuppaketid')
            ->where('status', $this->whereValid)
            ->where('status', '!=', 'Valid')
            ->first();

        $this->gTotalJual = $gTotalJual->totaljual;

        return view('livewire.main.penjualan.validasi', [
            'penjualanhds' => $this->dbPenjualanhds,
            'grandTotal' => $gTotalJual,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
