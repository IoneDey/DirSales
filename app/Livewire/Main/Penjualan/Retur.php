<?php

namespace App\Livewire\Main\Penjualan;

use App\Models\Penjualanhd;
use App\Models\Penjualanret;
use App\customClass\myNumber;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Retur extends Component {
    public $title = 'Retur Penjualan';

    public $nota = '';
    public $tgljual;
    public $timsetupid;
    public $tim;
    public $pt;
    public $kota;
    public $customernama;
    public $customeralamat;
    public $totaljual = 0;
    public $totaljualRp;

    public $tglretur;
    public $timsetuppaketid;
    public $barangid;
    public $namabarang;
    public $jumlahbarang;
    public $qty;
    public $harga;
    public $totalretur;

    public $maxretur;


    // pencarian by nota
    public $isNota = false;
    public $results;
    public $dbDetailJual;

    public function terbilang($number) {
        $number = abs($number);
        $words = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $result = "";

        if ($number < 12) {
            $result = " " . $words[$number];
        } elseif ($number < 20) {
            $result = $this->terbilang($number - 10) . " Belas ";
        } elseif ($number < 100) {
            $result = $this->terbilang(intval($number / 10)) . " Puluh " . $this->terbilang($number % 10);
        } elseif ($number < 200) {
            $result = " Seratus " . $this->terbilang($number - 100);
        } elseif ($number < 1000) {
            $result = $this->terbilang(intval($number / 100)) . " Ratus " . $this->terbilang($number % 100);
        } elseif ($number < 2000) {
            $result = " Seribu " . $this->terbilang($number - 1000);
        } elseif ($number < 1000000) {
            $result = $this->terbilang(intval($number / 1000)) . " Ribu " . $this->terbilang($number % 1000);
        } elseif ($number < 1000000000) {
            $result = $this->terbilang(intval($number / 1000000)) . " Juta " . $this->terbilang($number % 1000000);
        } elseif ($number < 1000000000000) {
            $result = $this->terbilang(intval($number / 1000000000)) . " Miliar " . $this->terbilang($number % 1000000000);
        } else {
            $result = "Angka terlalu besar";
        }

        return trim($result);
    }

    public function mount() {
        $this->tglretur = date("Y-m-d");
    }
    public function updatedqty() {
        $this->hitTotalRetur();
    }

    public function updatedharga() {
        $this->hitTotalRetur();
    }

    public function resetErrors() {
        $this->resetErrorBag();
    }

    // pilih nota
    function esc_chars($input) {
        $special_chars = [
            '\\' => '\\\\',
            '\'' => '\\\'',
            '"' => '\\"',
            '%' => '\\%',
            '_' => '\\_',
            ';' => '\\;',
            '--' => '\\--',
            '#' => '\\#'
        ];
        foreach ($special_chars as $char => $escaped_char) {
            $input = str_replace($char, $escaped_char, $input);
        }
        return $input;
    }

    public function updatednota() {
        $this->isNota = false;
        $this->clearDataNota();
        if ($this->nota) {
            $this->results = Penjualanhd::where('nota', 'like', '%' . $this->nota . '%')
                ->orWhere('customernama', 'like', '%' . $this->nota . '%')
                ->get();
        } else {
            $this->results = null;
        }
    }

    public function clearDataNota() {
        $this->resetErrors();
        $this->tgljual = "";
        $this->timsetupid = "";
        $this->tim = '';
        $this->pt = '';
        $this->kota = '';
        $this->customernama = '';
        $this->customeralamat = '';
    }

    public function selectNota($nota) {
        $this->nota = $nota;
        $this->isNota = true;

        $data = Penjualanhd::select(
            'penjualanhds.timsetupid',
            'penjualanhds.nota',
            'penjualanhds.tgljual',
            'penjualanhds.angsuranhari',
            'penjualanhds.angsuranperiode',
            'tims.nama as tim',
            'pts.nama as pt',
            'kotas.nama as kota',
            'penjualanhds.customernama',
            'penjualanhds.customeralamat',
            'penjualanhds.status',
            DB::raw('SUM((penjualandts.jumlah + penjualandts.jumlahkoreksi) * timsetuppakets.hargajual) as totaljual')
        )
            ->leftJoin('timsetups', 'timsetups.id', '=', 'penjualanhds.timsetupid')
            ->leftJoin('tims', 'tims.id', '=', 'timsetups.timid')
            ->leftJoin('pts', 'pts.id', '=', 'tims.ptid')
            ->leftJoin('kotas', 'kotas.id', '=', 'timsetups.kotaid')
            ->leftJoin('penjualandts', 'penjualanhds.id', '=', 'penjualandts.penjualanhdid')
            ->leftJoin('timsetuppakets', 'timsetuppakets.id', '=', 'penjualandts.timsetuppaketid')
            ->where('nota', $this->nota)
            ->groupBy(
                'penjualanhds.timsetupid',
                'penjualanhds.nota',
                'penjualanhds.tgljual',
                'penjualanhds.angsuranhari',
                'penjualanhds.angsuranperiode',
                'tims.nama',
                'pts.nama',
                'kotas.nama',
                'penjualanhds.customernama',
                'penjualanhds.customeralamat',
                'penjualanhds.status'
            )
            ->first();
        if ($data) {
            $this->tgljual = $data->tgljual;
            $this->timsetupid = $data->timsetupid;
            $this->tim = $data->tim;
            $this->pt = $data->pt;
            $this->kota = $data->kota;
            $this->customernama = $data->customernama;
            $this->customeralamat = $data->customeralamat;
            $this->totaljual = $data->totaljual;
            $this->totaljualRp = $this->terbilang($data->totaljual);
        }

        $this->getDetailBarang($nota);


        //ambil nilai jual
        // $datajmljual = DB::select('
        //                 SELECT
        //                     SUM((b.jumlah+b.jumlahkoreksi)*c.hargajual) AS totaljual
        //                 FROM penjualanhds a
        //                 LEFT JOIN penjualandts b ON a.id=b.penjualanhdid
        //                 LEFT JOIN timsetuppakets c ON c.id=b.timsetuppaketid
        //                 WHERE a.nota=?
        //         ', [$nota]);
        // $this->jmljual = $datajmljual[0]->totaljual;
    }
    // end pilih nota

    public function getDataRetur($timsetuppaketid, $barangid) {
        $escnota = $this->esc_chars($this->nota);

        $result = DB::select(
            "
            SELECT
                b.nota,c.nama as namapaket,e.nama as namabarang,
                a.jumlah,a.jumlahkoreksi, a.jumlah+a.jumlahkoreksi as jmljual,
                c.hargajual,
                COALESCE(f.qtyret,0) as qtyret,
                COALESCE(f.totalret,0) as totalret,
                (a.jumlah+a.jumlahkoreksi) - COALESCE(f.qtyret,0) as maxretur,
                d.barangid,
                d.timsetuppaketid
                FROM `penjualandts` a
                left join penjualanhds b on a.penjualanhdid=b.id
                left join timsetuppakets c on a.timsetuppaketid=c.id
                left join timsetupbarangs d on c.id=d.timsetuppaketid
                left join barangs e on d.barangid=e.id
                left join (
                    select
                        nota,timsetuppaketid,barangid,sum(qty) as qtyret,sum(qty*harga) totalret
                    from penjualanrets
                    group by nota,timsetuppaketid,barangid
                ) f on b.nota=f.nota and d.timsetuppaketid=f.timsetuppaketid and d.barangid=f.barangid
            where b.nota='$escnota'
                and d.timsetuppaketid='$timsetuppaketid'
                and d.barangid='$barangid'
            "
        );

        //dd($result[0]->namabarang);
        if (count($result) == 1) {
            $this->clearDataRetur();
            $this->timsetuppaketid = $timsetuppaketid;
            $this->barangid = $barangid;
            $this->namabarang = $result[0]->namabarang;
            $this->jumlahbarang = $result[0]->jmljual;
            $this->maxretur = $result[0]->maxretur;
        } else {
            $this->js('alert("Data lebih dari 1, hubungi IT untuk dilakukan cek data.")');
        }
    }

    public function clearDataRetur() {
        $this->timsetuppaketid = "";
        $this->barangid = "";
        $this->namabarang = "";
        $this->jumlahbarang = "";
        $this->qty = "";
        $this->harga = "";
        $this->totalretur = "";
        $this->maxretur = 0;
    }

    public function hitTotalRetur() {
        $this->harga = preg_replace('/[^\d,.]/', '', $this->harga);
        $numHarga = myNumber::str2Float($this->harga);
        $this->totalretur = number_format(floatval($this->qty ?? 0) * floatval($numHarga ?? 0), 0, '', '.');
    }

    public function simpan() {
        $maxQty = $this->maxretur ?? 0;
        $this->harga = myNumber::str2Float($this->harga);
        $rules = [
            'timsetupid' => ['required'],
            'tglretur' => ['required'],
            'nota' => ['required'],
            'timsetuppaketid' => ['required'],
            'barangid' => ['required'],
            'qty' => ['required', 'numeric', 'min:1', 'max:' . $maxQty],
            'harga' => ['required', 'numeric', 'min:1'],
        ];
        date_default_timezone_set('Asia/Jakarta');
        $noretur = date('ymdHis');

        $validate = $this->validate($rules);
        $validate['noretur'] = $noretur;
        $validate['userid'] = auth()->user()->id;

        try {
            Penjualanret::create($validate);

            $msg = 'Retur penjualan tersimpan dengan nomor:' . $noretur;
            session()->flash('ok', $msg);
            $this->getDetailBarang($this->nota);
            $this->clearDataRetur();
        } catch (\Exception $e) {
            //throw $th;
            if ($e->getCode() == 23000) {
                $errors = implode("\n", array('Terjadi kesalahan: ', 'Nomer retur sudah terpakai.', 'Simpan kembali setelah 1 menit.'));
            } else {
                $errors = implode("\n", array('Terjadi kesalahan: ', $e->getMessage()));
            }
            session()->flash('error', $errors);
        }

        //dump($formattedDate);

    }

    public function getDetailBarang($nota) {
        $escnota = $this->esc_chars($nota);
        $this->dbDetailJual = DB::select(
            "
            SELECT
                b.nota,c.nama as namapaket,e.nama as namabarang,
                a.jumlah,a.jumlahkoreksi, a.jumlah+a.jumlahkoreksi as jmljual,
                c.hargajual,
                COALESCE(f.qtyret,0) as qtyret,
                COALESCE(f.totalret,0) as totalret,
                (a.jumlah+a.jumlahkoreksi) - COALESCE(f.qtyret,0) as maxretur,
                d.barangid,
                d.timsetuppaketid
                FROM `penjualandts` a
                left join penjualanhds b on a.penjualanhdid=b.id
                left join timsetuppakets c on a.timsetuppaketid=c.id
                left join timsetupbarangs d on c.id=d.timsetuppaketid
                left join barangs e on d.barangid=e.id
                left join (
                    select
                        nota,timsetuppaketid,barangid,sum(qty) as qtyret,sum(qty*harga) totalret
                    from penjualanrets
                    group by nota,timsetuppaketid,barangid
                ) f on b.nota=f.nota and d.timsetuppaketid=f.timsetuppaketid and d.barangid=f.barangid
            where b.nota='$escnota'
            "
        );
    }

    public function render() {
        dd("Masih dalam pengembangan");

        return view(
            'livewire.main.penjualan.retur',
            [
                'dbDetailJuals' => $this->dbDetailJual,
            ]
        )->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
