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

    public function dynamicSalesQueryPivot($nota) {
        // Initial query to set @sql variable to NULL
        $escNota = $this->esc_chars($nota);
        DB::statement("SET @sql = NULL");

        // Second query to generate the dynamic part of the SQL
        $sql2 = "
        SELECT
          GROUP_CONCAT(DISTINCT
            CONCAT(
              'SUM(CASE WHEN e.nama = ''',
              e.nama,
              ''' THEN (b.jumlah + b.jumlahkoreksi) ELSE 0 END) AS ',
              REPLACE(e.nama, ' ', '')
            )
          ) INTO @sql
        FROM barangs e order by e.nama;";

        DB::statement($sql2);

        // Third query to construct the final SQL using the @sql variable
        $sql3 = "SET @sql = CONCAT(
            'SELECT
                a.created_at,
                a.tgljual,
                a.customernama,
                a.customernotelp,
                a.nota,
                a.namasales,
                a.customeralamat,
                a.fotonota,
                a.fotonotarekap,
                \"\" AS kecamatan,
                g.nama AS kota,
                h.name as user,
                a.namalock,
                a.pjadminnota,
                a.pjkolektornota,
                (SELECT
                    SUM((y.jumlah+y.jumlahkoreksi)*z.hargajual) AS totaljual
                FROM penjualanhds x
                LEFT JOIN penjualandts y ON x.id=y.penjualanhdid
                LEFT JOIN timsetuppakets z ON z.id=y.timsetuppaketid
                WHERE x.nota=a.nota) as omset,
                sum((b.jumlah + b.jumlahkoreksi) * d.hpp) AS hpp,
                ', @sql, '
            FROM penjualanhds AS a
            LEFT JOIN penjualandts AS b ON b.penjualanhdid = a.id
            LEFT JOIN timsetuppakets AS c ON c.id = b.timsetuppaketid
            LEFT JOIN timsetupbarangs AS d ON d.timsetuppaketid = c.id
            LEFT JOIN barangs AS e ON e.id = d.barangid
            LEFT JOIN timsetups AS f ON a.timsetupid = f.id
            LEFT JOIN kotas AS g ON f.kotaid = g.id
            LEFT JOIN users AS h ON a.userid = h.id
            WHERE
                a.nota = ''$escNota''
            GROUP BY
                a.created_at,a.tgljual,a.customernama,a.customernotelp,a.nota,
                a.namasales,a.customeralamat,a.fotonota,a.fotonotarekap,
                g.nama,h.name,a.namalock,a.pjadminnota,a.pjkolektornota');";

        DB::statement($sql3);

        // Retrieve the final SQL statement from the @sql variable
        $finalSql = DB::select("SELECT @sql AS final_sql");
        $finalSql = $finalSql[0]->final_sql;

        // Execute the final SQL query
        $results = DB::select($finalSql);

        // Return the results (you might want to format or process them further)
        return $results;
    }

    public function uploadToSpreadsheet() {
        $results1 = DB::table('penjualanhds as a')
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
                DB::raw("if(IFNULL(e.kode,'')<>'',e.kode,e.nama) AS namabarang"),
                DB::raw('(b.jumlah + b.jumlahkoreksi) AS jumlah'),
                'a.customernotelp',
                'a.nota',
                'a.namasales',
                'a.customeralamat',
                DB::raw("CONCAT('" . asset('storage/') . "/',a.fotonota) as fotonota"),
                'a.fotonotarekap',
                DB::raw("'' AS kecamatan"),
                'g.nama AS kota'
            )
            ->where('a.nota', '=', $this->nota)
            ->get();

        $dataArr = [];
        foreach ($results1 as $item) {
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
            ];
        }
        $jsonData1 = json_encode($dataArr);

        $results2 = $this->dynamicSalesQueryPivot($this->nota);
        $jsonData2 = json_encode($results2);

        $combinedData = array(
            'data1' => $jsonData1,
            'data2' => $jsonData2
        );

        try {
            $client = new Client();
            $response = $client->post('https://script.google.com/macros/s/AKfycbxgwjS6ESiZoufN4g--mdLV9h08Nvjc2o0bliDY_xzpDjjdiPs5Fm-MffNVLngEd9G3og/exec', [
                'json' => $combinedData,
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $body = $response->getBody()->getContents();

            if ($body == 'Data spreadsheet berhasil disimpan.') {
                try {
                    Penjualanhd::updateOrCreate(['nota' => $this->nota], ['sheet' => 1]);
                } catch (\Exception $e) {
                    session()->flash('error', 'Kesalahan simpan: ' . $e->getMessage());
                }
            }
            session()->flash('ok', $body);
        } catch (RequestException $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan HTTP: ' . $e->getMessage());
        } catch (\Exception $e) {
            session()->flash('error', 'Kesalahan umum: ' . $e->getMessage());
        }

        $this->btnDisables = false;
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


// //untuk script google spreadsheet
// function doPost(e) {
//     try {
//       var sheetC = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("control");
//       var controlCell = sheetC.getRange("A1");

//       var isInsertingData = controlCell.getValue() == "Yes";
//       var counter = 0;
//       while (isInsertingData && counter < 15) {
//         Utilities.sleep(1000); // Tunggu 1 detik sebelum memeriksa lagi
//         isInsertingData = controlCell.getValue() == "Yes";
//         counter++;
//       }

//       var payload = JSON.parse(e.postData.contents);
//       var data1 = JSON.parse(payload.data1);
//       var data2 = JSON.parse(payload.data2);


//       var status = "";
//       if (counter >= 15) {
//         status = "Gagal menyimpan data: Waktu tunggu terlampaui";
//       } else {
//       // Lakukan operasi lainnya jika berhasil
//         controlCell.setValue("Yes");

//         //sheet 1
//         var sheet1 = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Entry Penjualan");
//         var range = sheet1.getRange("A:A");
//         var lock = range.protect();
//         lock.addEditor(Session.getEffectiveUser());

//         var newData = [];
//         for (var i = 0; i < data1.length; i++) {
//           var obj = data1[i];
//           var rowData = [];
//           rowData.push(obj.created_at);
//           rowData.push(obj.tgljual);
//           rowData.push(obj.customernama);
//           rowData.push(obj.namabarang);
//           rowData.push(obj.jumlah);
//           rowData.push(obj.customernotelp);
//           rowData.push(obj.nota);
//           rowData.push(obj.namasales);
//           rowData.push(obj.customeralamat);
//           rowData.push(obj.fotonota);
//           rowData.push(obj.fotonotarekap);
//           rowData.push(obj.kecamatan);
//           rowData.push(obj.kota);
//           newData.push(rowData);
//         }
//         sheet1.getRange(sheet1.getLastRow() + 1, 1, newData.length, newData[0].length).setValues(newData);
//         lock.remove();

//         //sheet 2
//         var sheet2 = SpreadsheetApp.getActiveSpreadsheet().getSheetByName("Laporan Penjualan");
//         var lastRow = sheet2.getLastRow() + 1;

//         var range2 = sheet2.getRange("A:A");
//         var lock2 = range2.protect();
//         lock2.addEditor(Session.getEffectiveUser());

//         sheet2.getRange(lastRow, 1).setValue(data2[0].tgljual);
//         sheet2.getRange(lastRow, 2).setValue(data2[0].nota);
//         sheet2.getRange(lastRow, 3).setValue(data2[0].namasales);
//         sheet2.getRange(lastRow, 4).setValue(data2[0].customernama);
//         sheet2.getRange(lastRow, 5).setValue(data2[0].Kerudung);
//         sheet2.getRange(lastRow, 6).setValue(data2[0].Kipas);
//         sheet2.getRange(lastRow, 7).setValue(data2[0].Presto);
//         sheet2.getRange(lastRow, 8).setValue(data2[0].RegulatorTectum);
//         sheet2.getRange(lastRow, 9).setValue(data2[0].SealClamp);
//         sheet2.getRange(lastRow, 10).setValue(data2[0].Selang4Lapis);
//         sheet2.getRange(lastRow, 11).setValue(data2[0].SelangBaja);
//         sheet2.getRange(lastRow, 12).setValue(data2[0].Teapot);
//         sheet2.getRange(lastRow, 13).setValue(data2[0].Wajan);
//         sheet2.getRange(lastRow, 14).setValue(data2[0].omset);
//         sheet2.getRange(lastRow, 15).setValue(data2[0].hpp);
//         sheet2.getRange(lastRow, 16).setValue(data2[0].user);
//         sheet2.getRange(lastRow, 17).setValue(data2[0].namalock);
//         sheet2.getRange(lastRow, 18).setValue(data2[0].pjadminnota);
//         sheet2.getRange(lastRow, 19).setValue(data2[0].pjkolektornota);

//         lock2.remove();

//         controlCell.setValue("");
//         status = "Data spreadsheet berhasil disimpan.";
//       }

//       return ContentService.createTextOutput(status);
//     } catch (error) {
//       controlCell.setValue("");
//       return ContentService.createTextOutput("Terjadi kesalahan: " + error.message);
//     }
//   }
