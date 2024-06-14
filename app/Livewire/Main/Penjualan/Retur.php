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

    public $optJenisRetur = 'Paket';
    public $maxretur;


    // pencarian by nota
    public $isNota = false;
    public $results;
    public $dbDetailJual;

    public $selectedOption = 'Up';
    public $dbInfoAngsuran;

    public function getInformasiAngsuran($nota) {
        $escNota = $this->esc_chars($nota);

        if ($this->selectedOption == 'Up' || $this->selectedOption == 'Down') {
            // reschedule angsuran up
            $urutan = ($this->selectedOption == 'Up' ? 'asc' : 'desc');
            $Sql = "
            WITH RECURSIVE cteAngsuran AS (
                SELECT
                    timsetupid,
                    nota,
                    tgljual,
                    DATE_ADD( tgljual, INTERVAL angsuranhari DAY ) AS tglangsuran,
                    angsuranhari,
                    angsuranperiode,
                    1 AS noawal
                FROM
                    penjualanhds UNION ALL
                SELECT
                    t1.timsetupid,
                    t1.nota,
                    t1.tgljual,
                    DATE_ADD( t2.tglangsuran, INTERVAL t1.angsuranhari DAY ) AS tglangsuran,
                    t1.angsuranhari,
                    t1.angsuranperiode,
                    t2.noawal + 1 AS noawal
                FROM
                    penjualanhds t1
                    LEFT JOIN cteAngsuran t2 ON t1.nota = t2.nota and t1.timsetupid=t2.timsetupid
                WHERE
                    t2.noawal < t1.angsuranperiode
                ),
                cteAngsuranTagih AS (
                SELECT
                    a.timsetupid,
                    a.nota,
                    a.noawal,
                    a.tgljual,
                    a.tglangsuran,
                    b.tglpenagihan,
                    a.angsuranhari,
                    a.angsuranperiode,
                    (b.jumlahbayar+b.biayakomisi+b.biayaadmin) AS jmlpenagihan
                FROM
                    cteAngsuran a
                    LEFT JOIN penagihans b ON a.nota = b.nota and a.timsetupid=b.timsetupid
                    AND b.tglpenagihan BETWEEN a.tglangsuran
                    AND DATE_ADD( a.tglangsuran, INTERVAL a.angsuranhari - 1 DAY )
                ),
                cteFinalAngsuranProses AS (
                SELECT
                    *
                FROM
                    cteAngsuranTagih UNION ALL
                SELECT
                    a.timsetupid,
                    a.nota,
                    0 AS noawal,
                    b.tgljual,
                    COALESCE ( b.tglangsuran, a.tglpenagihan ) AS tglangsuran,
                    a.tglpenagihan,
                    NULL AS angsuranhari,
                    NULL AS angsuranperiode,
                    (a.jumlahbayar+a.biayakomisi+a.biayaadmin) AS jmlpenagihan
                FROM
                    penagihans a
                    LEFT JOIN cteAngsuranTagih b ON a.tglpenagihan = b.tglpenagihan
                    AND (a.jumlahbayar+a.biayakomisi+a.biayaadmin) = b.jmlpenagihan
                WHERE
                    b.tglpenagihan IS NULL
                ),
                `ctereturjual` AS (
                SELECT
                    `penjualanrets`.`timsetupid` AS `timsetupid`,
                    `penjualanrets`.`nota` AS `nota`,
                    sum((
                            `penjualanrets`.`qty` * `penjualanrets`.`harga`
                        )) AS `jumlahretur`
                FROM
                    `penjualanrets`
                GROUP BY
                    `penjualanrets`.`timsetupid`,
                    `penjualanrets`.`nota`
                ),
                `ctedataangsuran` AS (
                SELECT
                    `a`.`timsetupid` AS `timsetupid`,
                    `a`.`nota` AS `nota`,(
                        sum(((
                                    `b`.`jumlah` + `b`.`jumlahkoreksi`
                                    ) * `c`.`hargajual`
                            )) - avg( ifnull(`d`.`jumlahretur`,0) )) AS `totaljual`,
                    `a`.`angsuranhari` AS `angsuranhari`,
                    `a`.`angsuranperiode` AS `angsuranperiode`,((
                            sum(((
                                        `b`.`jumlah` + `b`.`jumlahkoreksi`
                                        ) * `c`.`hargajual`
                                )) - avg( ifnull(`d`.`jumlahretur`,0) )) / `a`.`angsuranperiode`
                        ) AS `perangsuran`,(
                        ceiling((((
                                        sum(((
                                                    `b`.`jumlah` + `b`.`jumlahkoreksi`
                                                    ) * `c`.`hargajual`
                                            )) - avg( ifnull(`d`.`jumlahretur`,0) )) / `a`.`angsuranperiode`
                                    ) / 1
                            )) * 1
                        ) AS `perangsuranUp`,(
                        floor((((
                                        sum(((
                                                    `b`.`jumlah` + `b`.`jumlahkoreksi`
                                                    ) * `c`.`hargajual`
                                            )) - avg( ifnull(`d`.`jumlahretur`,0) )) / `a`.`angsuranperiode`
                                    ) / 1
                            )) * 1
                    ) AS `perangsuranDown`,
                    floor(((
                                sum(((
                                            `b`.`jumlah` + `b`.`jumlahkoreksi`
                                            ) * `c`.`hargajual`
                                    )) - avg( ifnull(`d`.`jumlahretur`,0) )) / `a`.`angsuranperiode`
                        )) AS `perangsuranBulat`,
                    `a`.`status` AS `STATUS`
                FROM
                    (((
                                `penjualanhds` `a`
                                LEFT JOIN `penjualandts` `b` ON ((
                                        `a`.`id` = `b`.`penjualanhdid`
                                    )))
                            LEFT JOIN `timsetuppakets` `c` ON ((
                                    `c`.`id` = `b`.`timsetuppaketid`
                                )))
                        LEFT JOIN `ctereturjual` `d` ON (((
                                    `a`.`timsetupid` = `d`.`timsetupid`
                                    )
                            AND ( `a`.`nota` = `d`.`nota` ))))
                GROUP BY
                    `a`.`timsetupid`,
                    `a`.`nota`,
                    `a`.`angsuranhari`,
                    `a`.`angsuranperiode`,
                    `a`.`status`
                ),
                cteNormalAngsuran AS (
                SELECT
                    a.timsetupid,
                    a.nota,
                    b.totaljual,
                    b.perangsuran,
                    a.noawal AS angsuranke,
                    a.tglangsuran,
                    a.tglpenagihan,
                    a.jmlpenagihan,
                    b.STATUS AS statuspenjualan
                FROM
                    cteFinalAngsuranProses a
                    LEFT JOIN cteDataAngsuran b ON a.nota = b.nota and a.timsetupid=b.timsetupid
                    AND a.angsuranperiode = b.angsuranperiode
                    WHERE a.nota = '$escNota' and a.timsetupid='$this->timsetupid'
                ) ,
                cteTotalTagih AS (
                    SELECT
                        timsetupid, nota, max( tglpenagihan ) AS tglTerakhir, sum(jumlahbayar+biayakomisi+biayaadmin) AS totTertagih
                    FROM penagihans
                    WHERE nota = '$escNota' and timsetupid='$this->timsetupid'
                    GROUP BY timsetupid,nota
                ) , cteSisaPeriodeAngsuran AS
                (
                    select
                        a.timsetupid,a.nota,count(a.nota) as reAngsuranperiode
                    from cteNormalAngsuran a
                    left join cteTotalTagih b on a.nota=b.nota and a.timsetupid=b.timsetupid
                    where a.angsuranke<>0 and a.tglpenagihan is NULL
                    and a.tglangsuran > b.tglTerakhir
                    group by a.timsetupid, a.nota
                ), cteDataReschedulePenagihan AS
                (
                select
                    a.*,b.reAngsuranperiode
                from cteTotalTagih a
                left join cteSisaPeriodeAngsuran b on a.nota=b.nota and a.timsetupid=b.timsetupid
                ),
                cteReScheduleAngsuranF1 AS
                (
                    SELECT
                        angsuranke,
                        timsetupid,
                        nota,
                        max( totaljual ) AS totaljual,
                        avg(
                        COALESCE ( perangsuran, 0 )) AS totperangsuran,
                        sum(
                        COALESCE ( jmlpenagihan, 0 )) AS totjmlpenagihan,
                        avg(
                            COALESCE ( perangsuran, 0 )) - sum(
                        COALESCE ( jmlpenagihan, 0 )) AS selisihterbayar
                    FROM
                        cteNormalAngsuran
                    WHERE
                        tglangsuran <= COALESCE (( SELECT tglTerakhir FROM cteDataReschedulePenagihan ), tglangsuran )
                    GROUP BY
                        angsuranke,
                        timsetupid,
                        nota
            ), cteReScheduleAngsuranF1a as
            (
                SELECT
                    timsetupid,nota,avg(ifnull(totaljual,0)) as totaljual,
                    sum(totperangsuran) as totperangsuran,
                    sum(totjmlpenagihan) as totjmlpenagihan,
                    sum(totperangsuran) - sum(totjmlpenagihan) as selisihterbayar
                from cteReScheduleAngsuranF1
                GROUP BY
                timsetupid,
                nota
            ), cteReScheduleAngsuranF2 AS
            (
                SELECT
                    a.timsetupid,
                    a.nota,a.totaljual,
                    a.perangsuran as perangsuran,
                    a.angsuranke,a.tglangsuran,a.tglpenagihan,
                    a.jmlpenagihan,a.statuspenjualan,b.selisihterbayar,
                    ROW_NUMBER() OVER (order by tglangsuran $urutan) as urut
                FROM cteNormalAngsuran a
                left join cteReScheduleAngsuranF1a b on a.nota=b.nota and a.timsetupid=b.timsetupid
                where a.tglangsuran > COALESCE((select tglTerakhir from cteDataReschedulePenagihan),a.tglangsuran)
            ), cteReScheduleAngsuranF3 AS
            (
                select
                    timsetupid, nota,totaljual,perangsuran,angsuranke,tglangsuran,tglpenagihan,jmlpenagihan,statuspenjualan
                from cteNormalAngsuran
                where tglangsuran <= COALESCE((select tglTerakhir from cteDataReschedulePenagihan),tglangsuran)
                union all
                select
                    timsetupid,nota,totaljual,if(urut=1,selisihterbayar,0) + perangsuran as perangsuran,angsuranke,tglangsuran,tglpenagihan,jmlpenagihan,statuspenjualan
                from cteReScheduleAngsuranF2
            )
            select
                a.*,b.namapenagih
            from cteReScheduleAngsuranF3 a
            left join penagihans b on a.nota=b.nota and a.timsetupid=b.timsetupid and a.tglpenagihan=b.tglpenagihan
            order by a.tglangsuran,a.tglpenagihan,a.nota
        ";
        };
        $this->dbInfoAngsuran = DB::select($Sql);
    }

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
        $this->dbInfoAngsuran = null;
        $this->dbDetailJual = null;
    }

    public function selectNota($timsetupid, $nota) {
        $this->nota = $nota;
        $this->timsetupid = $timsetupid;
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
            ->where('penjualanhds.nota', $this->nota)
            ->where('penjualanhds.timsetupid', $this->timsetupid)
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
            'tglretur' => [
                'required', 'date',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) < strtotime($this->tgljual)) {
                        $fail('Tanggal retur harus lebih besar atau sama dengan tanggal jual.');
                    }
                }
            ],
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
            if ($this->optJenisRetur == 'Paket') {
                $sql =
                    "
                    SELECT * FROM `timsetupbarangs` where timsetuppaketid=$this->timsetuppaketid
                ";
                $datas = DB::select($sql);
                DB::transaction(function () use ($datas, $validate) {
                    foreach ($datas as $data) {
                        if ($data->barangid == $this->barangid) {
                            //dump($data->barangid, 'sama');
                            $validate['barangid'] = $data->barangid;
                            $validate['harga'] = $this->harga;
                            Penjualanret::create($validate);
                        } else {
                            //dump($data->barangid, 'tidak sama');
                            $validate['barangid'] = $data->barangid;
                            $validate['harga'] = 0;
                            Penjualanret::create($validate);
                        }
                    }
                });
            }

            if ($this->optJenisRetur == 'Ecer') {
                Penjualanret::create($validate);
            }

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
        $partimsetupid = $this->timsetupid ?? -1;

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
            where b.nota='$escnota' and b.timsetupid='$partimsetupid'
            "
        );
    }

    public function render() {
        //dd("Masih dalam pengembangan");
        $this->getInformasiAngsuran($this->nota);

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
