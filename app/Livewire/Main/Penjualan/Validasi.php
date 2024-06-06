<?php

namespace App\Livewire\Main\Penjualan;

use App\Http\Controllers\SendWaMessage;
use App\Models\Penjualanhd;
use App\Models\Timsetup;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Validasi extends Component {
    use WithPagination;
    use WithFileUploads;

    public $statusUser;
    public $title;
    public $whereValid;

    // public $dbPenjualanhds;
    public $gTotalJual;

    //cek data
    public $nota;
    public $penjualanhdid;

    public $timsetupid = 'Semua';

    public $dbTimsetups;
    public $validMessage;

    //filter tgl
    public $tglAwal;
    public $tglAkhir;

    //rules khusus
    public $setisinamalockval = true;
    public $setisifotonotaval = true;
    public $setisifotonotarekapval = true;

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

    protected $queryString = [
        'tglAwal' => ['except' => ''],
        'tglAkhir' => ['except' => ''],
        'cari' => ['except' => '']
    ];

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

    public function cekValidasi($timsetupid, $nota) {
        // cek kelengkapan entry data
        $validHeader = "";
        $validDetail = "";
        $this->validMessage = "";

        $cekheader = PenjualanHd::where('nota', $nota)->where('timsetupid', $timsetupid)->first();
        $this->penjualanhdid = $cekheader->id;
        $this->nota = $cekheader->nota;

        $this->setisinamalockval = (bool) $cekheader->jointimsetup->joinTim->setisinamalockval;
        $this->setisifotonotaval = (bool) $cekheader->jointimsetup->joinTim->setisifotonotaval;
        $this->setisifotonotarekapval = (bool) $cekheader->jointimsetup->joinTim->setisifotonotarekapval;

        if ($cekheader->customeralamat == '') {
            $validHeader .= 'alamat customer belum diisi.<br>';
        }
        if ($cekheader->shareloc == '') {
            $validHeader .= 'Share lokasi belum diisi.<br>';
        }

        if ($this->setisinamalockval) {
            if ($cekheader->namalock == '') {
                $validHeader .= 'Nama lock belum diisi.<br>';
            }
        }

        if ($this->setisifotonotaval) {
            if ($cekheader->fotonota == '') {
                $validHeader .= 'Foto nota belum diisi.<br><br>';
            }
        }

        if ($this->setisifotonotarekapval) {
            if ($cekheader->fotonotarekap == '') {
                $validHeader .= 'Foto nota rekap belum diisi.<br><br>';
            }
        }

        // cek kelengkapan detail barang data
        $cekdetail = PenjualanHd::leftJoin('penjualandts as b', 'b.penjualanhdid', '=', 'penjualanhds.id')
            ->leftJoin('timsetuppakets as c', function ($join) {
                $join->on('c.id', '=', 'b.timsetuppaketid')
                    ->leftJoin('timsetupbarangs as d', 'd.timsetuppaketid', '=', 'c.id');
            })
            ->where('penjualanhds.nota', $nota)
            ->where('penjualanhds.timsetupid', $timsetupid)
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

    public function updatedtglAwal() {
        $this->refresh();
    }

    public function updatedtglAkhir() {
        $this->refresh();
    }

    public function mount() {
        $this->tglAwal = date('Y-m-01'); // Mengambil tanggal pertama dari bulan ini
        $this->tglAkhir = date('Y-m-t'); // Mengambil tanggal terakhir dari bulan ini
        $this->dbTimsetups = Timsetup::get();

        $this->tglAwal = request()->query('tglAwal', $this->tglAwal);
        $this->tglAkhir = request()->query('tglAkhir', $this->tglAkhir);
        $this->cari = request()->query('cari', $this->cari);

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
        $startDate = Carbon::parse($this->tglAwal)->format('Y-m-d');
        $endDate = Carbon::parse($this->tglAkhir)->format('Y-m-d');

        $dbPenjualanhds = Penjualanhd::withSum('joinPenjualandt', DB::raw('jumlah + jumlahkoreksi'))
            ->whereBetween('tgljual', [$startDate, $endDate])
            ->where('status', $this->whereValid)
            ->where('status', '!=', 'Valid')
            ->where(($this->timsetupid == 'Semua' ? DB::raw('\'Semua\'') : 'penjualanhds.timsetupid'), $this->timsetupid)
            ->where(function ($query) {
                $query->where('penjualanhds.nota', 'like', '%' . $this->cari . '%')
                    ->orWhere('penjualanhds.customernama', 'like', '%' . $this->cari . '%')
                    ->orWhere('penjualanhds.customernotelp', 'like', '%' . $this->cari . '%');
            })
            ->paginate(10);
        return $dbPenjualanhds;
    }

    public function render() {
        $dbPenjualanhds = $this->refresh();

        $gTotalJual = Penjualanhd::selectRaw('sum((b.jumlah+b.jumlahkoreksi)*c.hargajual) as totaljual')
            ->leftJoin('penjualandts as b', 'penjualanhds.id', '=', 'b.penjualanhdid')
            ->leftJoin('timsetuppakets as c', 'c.id', '=', 'b.timsetuppaketid')
            ->whereBetween('tgljual', [$this->tglAwal, $this->tglAkhir])
            ->where('status', $this->whereValid)
            ->where('status', '!=', 'Valid')
            ->where(($this->timsetupid == 'Semua' ? DB::raw('\'Semua\'') : 'penjualanhds.timsetupid'), $this->timsetupid)
            ->where(function ($query) {
                $query->where('penjualanhds.nota', 'like', '%' . $this->cari . '%')
                    ->orWhere('penjualanhds.customernama', 'like', '%' . $this->cari . '%')
                    ->orWhere('penjualanhds.customernotelp', 'like', '%' . $this->cari . '%');
            })
            ->first();

        $this->gTotalJual = $gTotalJual->totaljual;

        return view('livewire.main.penjualan.validasi', [
            'penjualanhds' => $dbPenjualanhds,
            'grandTotal' => $gTotalJual,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
