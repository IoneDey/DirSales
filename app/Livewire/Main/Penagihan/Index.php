<?php

namespace App\Livewire\Main\Penagihan;

use App\Models\Penagihan;
use App\Models\Penjualanhd;
use App\customClass\myNumber;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component {
    use WithFileUploads;
    public $title = 'Penagihan';

    //untuk edit piutang
    public $id;
    public $isUpdate = false;

    // field
    public $timsetupid;
    public $nota = '';
    public $tim;
    public $pt;
    public $kota;
    public $customernama;
    public $customeralamat;

    public $tglpenagihan;
    public $namapenagih;
    public $jumlahbayar = 0;
    public $biayakomisi = 0;
    public $biayaadmin = 0;
    public $jumlah;
    public $fotokwitansi;

    public $tgljual;
    public $jmljual;
    public $angsuranperiode;
    public $angsuranhari;
    public $tglAngsuranAkhir;

    // pencarian by nota
    public $isNota = false;
    public $results;

    public $dbKartuPiutang;
    public $dbInfoAngsuran;
    public $dbInfoSPK;

    public $dbKolektors;

    public $selectedOption = 'Up';

    public $rotation = 0;
    public function rotate() {
        $this->rotation += 90;
        if ($this->rotation >= 360) {
            $this->rotation = 0;
        }
    }

    public function mount($id) {
        $this->id = $id;
        $this->title = 'Penagihan' . ($id == 0 ? '' : ' - Update');
        if ($this->id != 0) {
            $this->isUpdate = true;
            $this->getDataPiutang($this->id);
        }

        $this->dbKolektors = DB::select("SELECT nama FROM `karyawans` where void=0 and flagkolektor=1");
    }

    public function getDataPiutang($id) {
        $data = Penagihan::find($id);
        if ($data) {
            $this->timsetupid = $data->timsetupid;
            $this->nota = $data->nota;
            $this->selectNota($this->timsetupid, $this->nota);

            $this->tglpenagihan = $data->tglpenagihan;
            $this->namapenagih = $data->namapenagih;
            $this->jumlahbayar = number_format(floatval($data->jumlahbayar), 0, '', '.');
            $this->biayakomisi = number_format(floatval($data->biayakomisi), 0, '', '.');
            $this->biayaadmin = number_format(floatval($data->biayaadmin), 0, '', '.');
            $this->jumlah = number_format(floatval($data->jumlahbayar + $data->biayakomisi + $data->biayaadmin), 0, '', '.');
            $this->fotokwitansi = $data->fotokwitansi;
        }
    }

    public function updatedtglpenagihan() {
        $this->dbInfoSPK = null;
        $partimsetupid = $this->timsetupid ?? -1;
        if (!$partimsetupid) {
            $partimsetupid = -1;
        }

        $query = "
            select
                timsetupid,nota,avg(totaljual) as totaljual,
                avg(perangsuran) as perangsuran,angsuranke,tglangsuran,
                sum(jmlpenagihan) as jmlpenagihan, angsuranhari
            from vwListAngsuran
            where nota='$this->nota' and timsetupid=$partimsetupid and
            DATE_FORMAT('$this->tglpenagihan','%Y-%m-%d') BETWEEN tglangsuran and DATE_ADD(tglangsuran,INTERVAL angsuranhari-1 day)
            group by timsetupid,nota,angsuranke,tglangsuran,angsuranhari
        ";
        $result = db::select($query);

        $this->tglAngsuranAkhir = new DateTime($this->tgljual);
        $interval = ($this->angsuranhari * $this->angsuranperiode) . ' days';
        $this->tglAngsuranAkhir->modify($interval);
        $this->tglAngsuranAkhir = $this->tglAngsuranAkhir->format('Y-m-d');

        if (!empty($result)) {
            $result = $result[0];
        }
        $this->dbInfoSPK = $result;
        //dump($result);
    }

    public function updatedjumlahbayar() {
        $jumlahbayar = myNumber::str2Float($this->jumlahbayar ?? 0) + myNumber::str2Float($this->biayakomisi ?? 0) + myNumber::str2Float($this->biayaadmin ?? 0);
        $this->jumlah = number_format($jumlahbayar, 0, '', '.');
    }

    public function updatedbiayakomisi() {
        $jumlahbayar = myNumber::str2Float($this->jumlahbayar ?? 0) + myNumber::str2Float($this->biayakomisi ?? 0) + myNumber::str2Float($this->biayaadmin ?? 0);
        $this->jumlah = number_format($jumlahbayar, 0, '', '.');
    }

    public function updatedbiayaadmin() {
        $jumlahbayar = myNumber::str2Float($this->jumlahbayar ?? 0) + myNumber::str2Float($this->biayakomisi ?? 0) + myNumber::str2Float($this->biayaadmin ?? 0);
        $this->jumlah = number_format($jumlahbayar, 0, '', '.');
    }

    public function resetErrors() {
        $this->resetErrorBag();
    }

    public function updatedselectedOption() {
        if ($this->nota) {
            $this->getKartuPiutangNota($this->nota);
            $this->getInformasiAngsuran($this->nota);
        }
    }

    public function getKartuPiutangNota($nota) {
        $escNota = $this->esc_chars($nota);
        $Sql = "
            SELECT
                X.*,
                @saldo := @saldo + (IFNULL(x.debet, 0) - x.kredit) AS saldo
            FROM
            (
            SELECT
                a.tgljual, a.timsetupid, g.nama AS tim,a.nota,SUM((b.jumlah+b.jumlahkoreksi)*c.hargajual) AS debet, 0 AS kredit
            FROM penjualanhds a
            LEFT JOIN penjualandts b ON b.penjualanhdid=a.id
            LEFT JOIN timsetups f ON f.id=a.timsetupid
            LEFT JOIN tims g ON g.id=f.timid
            LEFT JOIN timsetuppakets c ON c.id=b.timsetuppaketid
            WHERE a.nota = '$escNota' and a.timsetupid='$this->timsetupid'
            GROUP BY a.tgljual, a.timsetupid, g.nama,a.nota
            UNION ALL
            SELECT
                tglretur, timsetupid, '' as tim,noretur,0 as debet,sum(qty*harga) as kredit
            FROM penjualanrets
            where nota='$escNota' and timsetupid='$this->timsetupid'
            group by tglretur,timsetupid,noretur
            UNION ALL
            SELECT
                tglpenagihan, a.timsetupid, g.nama AS Tim,CONCAT(nota,'-', DATE_FORMAT(tglpenagihan, '%Y%m%d')) AS nota,0 AS debet, (jumlahbayar+biayakomisi+biayaadmin) AS kredit
            FROM penagihans a
            LEFT JOIN timsetups f ON f.id=a.timsetupid
            LEFT JOIN tims g ON g.id=f.timid
            WHERE a.nota = '$escNota' and a.timsetupid='$this->timsetupid'
            ) X
            CROSS
            JOIN (
            SELECT @saldo := 0) AS vars
            ORDER BY X.tgljual
        ";
        $this->dbKartuPiutang = DB::select($Sql);
    }

    public function getInformasiAngsuran($nota) {
        $escNota = $this->esc_chars($nota);

        // reschedule angsuran rata2
        if ($this->selectedOption == 'Avg') {
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
                cteDataAngsuran AS (
                SELECT
                    a.timsetupid,
                    a.nota,
                    SUM(( b.jumlah + b.jumlahkoreksi )* c.hargajual ) AS totaljual,
                    a.angsuranhari,
                    a.angsuranperiode,
                    SUM(( b.jumlah + b.jumlahkoreksi ) * c.hargajual ) / a.angsuranperiode AS perangsuran,
                    CEIL( SUM(( b.jumlah + b.jumlahkoreksi ) * c.hargajual ) / a.angsuranperiode / 1 ) * 1 AS perangsuranUp,
                    FLOOR( SUM(( b.jumlah + b.jumlahkoreksi ) * c.hargajual ) / a.angsuranperiode / 1 ) * 1 AS perangsuranDown,
                    FLOOR( SUM(( b.jumlah + b.jumlahkoreksi ) * c.hargajual ) / a.angsuranperiode ) AS perangsuranBulat,
                    a.STATUS
                FROM
                    penjualanhds a
                    LEFT JOIN penjualandts b ON a.id = b.penjualanhdid
                    LEFT JOIN timsetuppakets c ON c.id = b.timsetuppaketid
                GROUP BY
                    a.timsetupid,
                    a.nota,
                    a.angsuranhari,
                    a.angsuranperiode,
                    a.STATUS
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
                ),
                cteTotalTagih AS (
                    SELECT
                        timsetupid, nota, max( tglpenagihan ) AS tglTerakhir, sum(jumlahbayar+biayakomisi+biayaadmin) AS totTertagih
                    FROM penagihans
                    WHERE nota = '$escNota' and timsetupid='$this->timsetupid'
                    GROUP BY timsetupid,nota
                ), cteSisaPeriodeAngsuran AS
                (
                    select
                        a.timsetupid, a.nota,count(a.nota) as reAngsuranperiode
                    from cteNormalAngsuran a
                    left join cteTotalTagih b on a.nota=b.nota and a.timsetupid=b.timsetupid
                    where a.angsuranke<>0 and a.tglpenagihan is NULL
                    and a.tglangsuran > b.tglTerakhir
                    group by a.timsetupid,a.nota
                ), cteDataReschedulePenagihan AS
                (
                select
                    a.*,b.reAngsuranperiode
                from cteTotalTagih a
                left join cteSisaPeriodeAngsuran b on a.nota=b.nota and a.timsetupid=b.timsetupid
                ), cteReScheduleAngsuran AS
                (
                    SELECT
                        a.timsetupid, a.nota,a.totaljual,perangsuran,angsuranke,tglangsuran,tglpenagihan,jmlpenagihan,statuspenjualan
                    FROM
                        cteNormalAngsuran a
                    where a.tglangsuran <= COALESCE((select tglTerakhir from cteDataReschedulePenagihan),a.tglangsuran)
                    union all
                    SELECT
                        a.timsetupid, a.nota,a.totaljual,(a.totaljual-b.totTertagih) / reAngsuranperiode as perangsuran,a.angsuranke,a.tglangsuran,a.tglpenagihan,
                        a.jmlpenagihan,a.statuspenjualan
                    FROM cteNormalAngsuran a
                    left join cteDataReschedulePenagihan b on a.nota=b.nota and a.timsetupid=b.timsetupid
                    where a.tglangsuran > COALESCE((select tglTerakhir from cteDataReschedulePenagihan),a.tglangsuran)
            )
            select * from cteReScheduleAngsuran
            ORDER BY
                tglangsuran,
                tglpenagihan,
                nota;
        ";
        };

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
        $this->timsetupid = "";
        $this->tim = '';
        $this->pt = '';
        $this->kota = '';
        $this->customernama = '';
        $this->customeralamat = '';
        $this->tglpenagihan = "";
        $this->namapenagih = "";
        $this->jumlahbayar = 0;
        $this->biayakomisi = 0;
        $this->biayaadmin = 0;
        $this->jumlah = "";
        $this->tgljual = "";
        $this->jmljual = 0;
        $this->angsuranhari = "";
        $this->angsuranperiode = "";
        $this->fotokwitansi = null;
        $this->dbInfoSPK = null;
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
            'penjualanhds.status'
        )
            ->leftJoin('timsetups', 'timsetups.id', '=', 'penjualanhds.timsetupid')
            ->leftJoin('tims', 'tims.id', '=', 'timsetups.timid')
            ->leftJoin('pts', 'pts.id', '=', 'tims.ptid')
            ->leftJoin('kotas', 'kotas.id', '=', 'timsetups.kotaid')
            ->where('nota', $this->nota)
            ->where('timsetupid', $this->timsetupid)
            ->first();
        if ($data) {
            $this->timsetupid = $data->timsetupid;
            $this->tim = $data->tim;
            $this->pt = $data->pt;
            $this->kota = $data->kota;
            $this->customernama = $data->customernama;
            $this->customeralamat = $data->customeralamat;
            $this->tgljual = $data->tgljual;
            $this->angsuranhari = $data->angsuranhari;
            $this->angsuranperiode = $data->angsuranperiode;
        }


        //ambil nilai jual
        $datajmljual = DB::select('
                        SELECT
                            SUM((b.jumlah+b.jumlahkoreksi)*c.hargajual) AS totaljual
                        FROM penjualanhds a
                        LEFT JOIN penjualandts b ON a.id=b.penjualanhdid
                        LEFT JOIN timsetuppakets c ON c.id=b.timsetuppaketid
                        WHERE a.nota=? and a.timsetupid=?
                ', [$nota, $timsetupid]);
        $this->jmljual = $datajmljual[0]->totaljual;
    }
    // end pilih nota

    public function clear() {
        $this->timsetupid = "";
        $this->nota = "";
        $this->tim = '';
        $this->pt = '';
        $this->kota = '';
        $this->customernama = '';
        $this->customeralamat = '';
        $this->tglpenagihan = "";
        $this->namapenagih = "";
        $this->jumlahbayar = 0;
        $this->biayakomisi = 0;
        $this->biayaadmin = 0;
        $this->jumlah = "";
        $this->tgljual = "";
        $this->jmljual = null;
        $this->angsuranhari = "";
        $this->angsuranperiode = "";
        $this->fotokwitansi = null;

        $this->isNota = false;
        $this->results = null;
        $this->dbKartuPiutang = null;
        $this->dbInfoAngsuran = null;
        $this->dbInfoSPK = null;
        $this->resetErrors();
    }

    public function clearEntry() {
        $this->tglpenagihan = null;
        $this->dbInfoSPK = null;
        $this->namapenagih = null;
        $this->jumlahbayar = '';
        $this->biayakomisi = '';
        $this->biayaadmin = '';
        $this->jumlah = '';
        $this->fotokwitansi = null;
    }

    public function create() {
        $this->jumlah = myNumber::str2Float($this->jumlah);
        $this->jumlahbayar = myNumber::str2Float($this->jumlahbayar);
        $this->biayakomisi = myNumber::str2Float($this->biayakomisi);
        $this->biayaadmin = myNumber::str2Float($this->biayaadmin);

        $rules = [
            'nota' => [
                'required', 'min:15', 'max:15',
                Rule::unique('penagihans')->where(function ($query) {
                    return $query->where('nota', $this->nota)
                        ->where('tglpenagihan', $this->tglpenagihan)
                        ->where('timsetupid', $this->timsetupid);
                })
            ],
            'tglpenagihan' => [
                'required', 'date',
                function ($attribute, $value, $fail) {
                    if (strtotime($value) < strtotime($this->tgljual)) {
                        $fail('Tanggal penagihan harus lebih besar atau sama dengan tanggal jual.');
                    }
                }
            ],
            'namapenagih' => ['required', 'string', 'max:150'],
            'jumlah' => ['required', 'numeric', 'min:1'],
            'jumlahbayar' => ['required', 'numeric', 'min:0'],
            'biayakomisi' => ['required', 'numeric', 'min:0'],
            'biayaadmin' => ['required', 'numeric', 'min:0'],
            'fotokwitansi' => ['required', 'sometimes', 'image', 'max:1024'],
        ];

        $validate = $this->validate($rules);

        try {
            if ($this->fotokwitansi) {
                $filename = 'kwi-' . $this->nota . $this->timsetupid . $this->tglpenagihan . '.jpg';
                $validate['fotokwitansi'] = $this->fotokwitansi->storeAs('upkwitansi', $filename, 'public');
            }

            $validate['timsetupid'] = $this->timsetupid;
            $validate['userid'] = auth()->user()->id;
            Penagihan::create($validate);

            $this->jumlah = number_format($this->jumlah, 0, '', '.');
            $this->jumlahbayar = number_format($this->jumlahbayar, 0, '', '.');
            $this->biayakomisi = number_format($this->biayakomisi, 0, '', '.');
            $this->biayaadmin = number_format($this->biayaadmin, 0, '', '.');
            $this->js('alert("Data penagiahn nota: ' . $this->nota . ' sudah tersimpan.")');
            $this->clearEntry();
        } catch (\Exception $e) {
        }
    }

    public function update() {
        dd('masih dalam pengembangan');
    }

    public function render() {

        $this->getKartuPiutangNota($this->nota);
        $this->getInformasiAngsuran($this->nota);

        return view('livewire.main.penagihan.index', [
            'dbKartus' => $this->dbKartuPiutang,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
