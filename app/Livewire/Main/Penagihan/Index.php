<?php

namespace App\Livewire\Main\Penagihan;

use App\Models\Penagihan;
use App\Models\Penjualanhd;
use App\customClass\myNumber;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class Index extends Component {
    public $title = 'Penagihan';

    public $timsetupid;
    public $nota = '';
    public $tim;
    public $pt;
    public $kota;
    public $customernama;
    public $customeralamat;

    public $tglpenagihan;
    public $namapenagih;
    public $jumlah;
    public $fotokwitansi;

    public $tgljual;
    public $jmljual;
    public $angsuranperiode;
    public $angsuranhari;

    // pencarian by nota
    public $isNota = false;
    public $results;

    public $dbKartuPiutang;
    public $dbInfoAngsuran;

    public $selectedOption = 'Avg';

    public function resetErrors() {
        $this->resetErrorBag();
    }

    public function updatedselectedOption() {
        if ($this->nota) {
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
                a.tgljual, g.nama AS tim,a.nota,SUM((b.jumlah+b.jumlahkoreksi)*c.hargajual) AS debet, 0 AS kredit
            FROM penjualanhds a
            LEFT JOIN penjualandts b ON b.penjualanhdid=a.id
            LEFT JOIN timsetups f ON f.id=a.timsetupid
            LEFT JOIN tims g ON g.id=f.timid
            LEFT JOIN timsetuppakets c ON c.id=b.timsetuppaketid
            WHERE a.nota = '$escNota'
            GROUP BY a.tgljual, g.nama,a.nota
            UNION ALL
            SELECT
                tglretur,'' as tim,noretur,0 as debet,qty*harga as kredit
            FROM penjualanrets
            where nota='$escNota'
            UNION ALL
            SELECT
                tglpenagihan,g.nama AS Tim,CONCAT(nota,'-', DATE_FORMAT(tglpenagihan, '%Y%m%d')) AS nota,0 AS debet, jumlah AS kredit
            FROM penagihans a
            LEFT JOIN timsetups f ON f.id=a.timsetupid
            LEFT JOIN tims g ON g.id=f.timid
            WHERE a.nota = '$escNota'
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
                    nota,
                    tgljual,
                    DATE_ADD( tgljual, INTERVAL angsuranhari DAY ) AS tglangsuran,
                    angsuranhari,
                    angsuranperiode,
                    1 AS noawal
                FROM
                    penjualanhds UNION ALL
                SELECT
                    t1.nota,
                    t1.tgljual,
                    DATE_ADD( t2.tglangsuran, INTERVAL t1.angsuranhari DAY ) AS tglangsuran,
                    t1.angsuranhari,
                    t1.angsuranperiode,
                    t2.noawal + 1 AS noawal
                FROM
                    penjualanhds t1
                    LEFT JOIN cteAngsuran t2 ON t1.nota = t2.nota
                WHERE
                    t2.noawal < t1.angsuranperiode
                ),
                cteAngsuranTagih AS (
                SELECT
                    a.nota,
                    a.noawal,
                    a.tgljual,
                    a.tglangsuran,
                    b.tglpenagihan,
                    a.angsuranhari,
                    a.angsuranperiode,
                    b.jumlah AS jmlpenagihan
                FROM
                    cteAngsuran a
                    LEFT JOIN penagihans b ON a.nota = b.nota
                    AND b.tglpenagihan BETWEEN a.tglangsuran
                    AND DATE_ADD( a.tglangsuran, INTERVAL a.angsuranhari - 1 DAY )
                ),
                cteFinalAngsuranProses AS (
                SELECT
                    *
                FROM
                    cteAngsuranTagih UNION ALL
                SELECT
                    a.nota,
                    0 AS noawal,
                    b.tgljual,
                    COALESCE ( b.tglangsuran, a.tglpenagihan ) AS tglangsuran,
                    a.tglpenagihan,
                    NULL AS angsuranhari,
                    NULL AS angsuranperiode,
                    a.jumlah AS jmlpenagihan
                FROM
                    penagihans a
                    LEFT JOIN cteAngsuranTagih b ON a.tglpenagihan = b.tglpenagihan
                    AND a.jumlah = b.jmlpenagihan
                WHERE
                    b.tglpenagihan IS NULL
                ),
                cteDataAngsuran AS (
                SELECT
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
                    a.nota,
                    a.angsuranhari,
                    a.angsuranperiode,
                    a.STATUS
                ),
                cteNormalAngsuran AS (
                SELECT
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
                    LEFT JOIN cteDataAngsuran b ON a.nota = b.nota
                    AND a.angsuranperiode = b.angsuranperiode
                    WHERE a.nota = '$escNota'
                ),
                cteTotalTagih AS (
                    SELECT
                        nota, max( tglpenagihan ) AS tglTerakhir, sum( jumlah ) AS totTertagih
                    FROM penagihans
                    WHERE nota = '$escNota'
                    GROUP BY nota
                ), cteSisaPeriodeAngsuran AS
                (
                    select
                        a.nota,count(a.nota) as reAngsuranperiode
                    from cteNormalAngsuran a
                    left join cteTotalTagih b on a.nota=b.nota
                    where a.angsuranke<>0 and a.tglpenagihan is NULL
                    and a.tglangsuran > b.tglTerakhir
                    group by a.nota
                ), cteDataReschedulePenagihan AS
                (
                select
                    a.*,b.reAngsuranperiode
                from cteTotalTagih a
                left join cteSisaPeriodeAngsuran b on a.nota=b.nota
                ), cteReScheduleAngsuran AS
                (
                    SELECT
                        a.nota,a.totaljual,perangsuran,angsuranke,tglangsuran,tglpenagihan,jmlpenagihan,statuspenjualan
                    FROM
                        cteNormalAngsuran a
                    where a.tglangsuran <= COALESCE((select tglTerakhir from cteDataReschedulePenagihan),a.tglangsuran)
                    union all
                    SELECT
                        a.nota,a.totaljual,(a.totaljual-b.totTertagih) / reAngsuranperiode as perangsuran,a.angsuranke,a.tglangsuran,a.tglpenagihan,
                        a.jmlpenagihan,a.statuspenjualan
                    FROM cteNormalAngsuran a
                    left join cteDataReschedulePenagihan b on a.nota=b.nota
                    where a.tglangsuran > COALESCE((select tglTerakhir from cteDataReschedulePenagihan),a.tglangsuran)
            )
            select * from cteReScheduleAngsuran
            ORDER BY
                nota,
                tglangsuran,
                tglpenagihan;
        ";
        };

        if ($this->selectedOption == 'Up' || $this->selectedOption == 'Down') {
            // reschedule angsuran up
            $urutan = ($this->selectedOption == 'Up' ? 'asc' : 'desc');
            $Sql = "
            WITH RECURSIVE cteAngsuran AS (
                SELECT
                    nota,
                    tgljual,
                    DATE_ADD( tgljual, INTERVAL angsuranhari DAY ) AS tglangsuran,
                    angsuranhari,
                    angsuranperiode,
                    1 AS noawal
                FROM
                    penjualanhds UNION ALL
                SELECT
                    t1.nota,
                    t1.tgljual,
                    DATE_ADD( t2.tglangsuran, INTERVAL t1.angsuranhari DAY ) AS tglangsuran,
                    t1.angsuranhari,
                    t1.angsuranperiode,
                    t2.noawal + 1 AS noawal
                FROM
                    penjualanhds t1
                    LEFT JOIN cteAngsuran t2 ON t1.nota = t2.nota
                WHERE
                    t2.noawal < t1.angsuranperiode
                ),
                cteAngsuranTagih AS (
                SELECT
                    a.nota,
                    a.noawal,
                    a.tgljual,
                    a.tglangsuran,
                    b.tglpenagihan,
                    a.angsuranhari,
                    a.angsuranperiode,
                    b.jumlah AS jmlpenagihan
                FROM
                    cteAngsuran a
                    LEFT JOIN penagihans b ON a.nota = b.nota
                    AND b.tglpenagihan BETWEEN a.tglangsuran
                    AND DATE_ADD( a.tglangsuran, INTERVAL a.angsuranhari - 1 DAY )
                ),
                cteFinalAngsuranProses AS (
                SELECT
                    *
                FROM
                    cteAngsuranTagih UNION ALL
                SELECT
                    a.nota,
                    0 AS noawal,
                    b.tgljual,
                    COALESCE ( b.tglangsuran, a.tglpenagihan ) AS tglangsuran,
                    a.tglpenagihan,
                    NULL AS angsuranhari,
                    NULL AS angsuranperiode,
                    a.jumlah AS jmlpenagihan
                FROM
                    penagihans a
                    LEFT JOIN cteAngsuranTagih b ON a.tglpenagihan = b.tglpenagihan
                    AND a.jumlah = b.jmlpenagihan
                WHERE
                    b.tglpenagihan IS NULL
                ),
                cteDataAngsuran AS (
                SELECT
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
                    a.nota,
                    a.angsuranhari,
                    a.angsuranperiode,
                    a.STATUS
                ),
                cteNormalAngsuran AS (
                SELECT
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
                    LEFT JOIN cteDataAngsuran b ON a.nota = b.nota
                    AND a.angsuranperiode = b.angsuranperiode
                    WHERE a.nota = '$escNota'
                ) ,
                cteTotalTagih AS (
                    SELECT
                        nota, max( tglpenagihan ) AS tglTerakhir, sum(jumlah) AS totTertagih
                    FROM penagihans
                    WHERE nota = '$escNota'
                    GROUP BY nota
                ) , cteSisaPeriodeAngsuran AS
                (
                    select
                            a.nota,count(a.nota) as reAngsuranperiode
                    from cteNormalAngsuran a
                    left join cteTotalTagih b on a.nota=b.nota
                    where a.angsuranke<>0 and a.tglpenagihan is NULL
                    and a.tglangsuran > b.tglTerakhir
                    group by a.nota
                ), cteDataReschedulePenagihan AS
                (
                select
                    a.*,b.reAngsuranperiode
                from cteTotalTagih a
                left join cteSisaPeriodeAngsuran b on a.nota=b.nota
                )	,
                cteReScheduleAngsuranF1 AS
                (
                    SELECT
                        nota,
                        max(totaljual) as totaljual,
                        sum(COALESCE(perangsuran,0)) as totperangsuran ,
                        sum(COALESCE(jmlpenagihan,0)) as totjmlpenagihan,
                        sum(COALESCE(perangsuran,0)) - sum(COALESCE(jmlpenagihan,0)) as selisihterbayar
                    FROM
                        cteNormalAngsuran
                    where tglangsuran <= COALESCE((select tglTerakhir from cteDataReschedulePenagihan),tglangsuran)
                    group by nota
            ), cteReScheduleAngsuranF2 AS
            (
                    SELECT
                        a.nota,a.totaljual,
                        a.perangsuran as perangsuran,
                        a.angsuranke,a.tglangsuran,a.tglpenagihan,
                        a.jmlpenagihan,a.statuspenjualan,b.selisihterbayar,
                        ROW_NUMBER() OVER (order by tglangsuran $urutan) as urut
                    FROM cteNormalAngsuran a
                    left join cteReScheduleAngsuranF1 b on a.nota=b.nota
                    where a.tglangsuran > COALESCE((select tglTerakhir from cteDataReschedulePenagihan),a.tglangsuran)
            ), cteReScheduleAngsuranF3 AS
            (
                select
                        nota,totaljual,perangsuran,angsuranke,tglangsuran,tglpenagihan,jmlpenagihan,statuspenjualan
                from cteNormalAngsuran
                where tglangsuran <= COALESCE((select tglTerakhir from cteDataReschedulePenagihan),tglangsuran)
                union all
                select
                    nota,totaljual,if(urut=1,selisihterbayar,0) + perangsuran as perangsuran,angsuranke,tglangsuran,tglpenagihan,jmlpenagihan,statuspenjualan
                from cteReScheduleAngsuranF2
            )
            select * from cteReScheduleAngsuranF3
            order by nota,tglangsuran,tglpenagihan
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
        $this->jumlah = "";
        $this->tgljual = "";
        $this->jmljual = 0;
        $this->angsuranhari = "";
        $this->angsuranperiode = "";
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
            'penjualanhds.status'
        )
            ->leftJoin('timsetups', 'timsetups.id', '=', 'penjualanhds.timsetupid')
            ->leftJoin('tims', 'tims.id', '=', 'timsetups.timid')
            ->leftJoin('pts', 'pts.id', '=', 'tims.ptid')
            ->leftJoin('kotas', 'kotas.id', '=', 'timsetups.kotaid')
            ->where('nota', $this->nota)
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
                        WHERE a.nota=?
                ', [$nota]);
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
        $this->jumlah = "";
        $this->tgljual = "";
        $this->jmljual = null;
        $this->angsuranhari = "";
        $this->angsuranperiode = "";
        $this->isNota = false;
        $this->results = null;
        $this->dbKartuPiutang = null;
        $this->dbInfoAngsuran = null;
        $this->resetErrors();
    }

    public function create() {
        $this->jumlah = myNumber::str2Float($this->jumlah);

        $rules = [
            'nota' => [
                'required', 'min:15', 'max:15',
                Rule::unique('penagihans')->where(function ($query) {
                    return $query->where('nota', $this->nota)
                        ->where('timsetupid', $this->timsetupid)
                        ->where('tglpenagihan', $this->tglpenagihan);
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
        ];

        $validate = $this->validate($rules);
        $validate['timsetupid'] = $this->timsetupid;
        $validate['userid'] = auth()->user()->id;
        Penagihan::create($validate);

        // $this->jumlah = myNumber::float2Str($this->jumlah);
        $this->jumlah = number_format($this->jumlah, 0, '', '.');
        $this->js('alert("Data penagiahn nota: ' . $this->nota . ' sudah tersimpan.")');
    }

    public function render() {
        dd("Masih dalam pengembangan");

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
