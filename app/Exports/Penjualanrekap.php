<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Penjualanrekap implements FromCollection, WithHeadings {
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $data;

    public function __construct(Collection $data) {
        $this->data = $data;
    }

    public function headings(): array {
        return [
            'Tim',
            'Tanggal Jual',
            'Nota',
            'Nama Sales',
            'Nama Customer',
            'No Telp Customer',
            'Alamat Customer',
            'Kecamatan',
            'Share Location',
            'Omset',
            'HPP',
            'Angsuran Periode',
            'Angsuran Hari',
            'Kerudung',
            'Kipas',
            'Presto',
            'Regulator Tectum',
            'Seal Clamp',
            'Selang 4 Lapis',
            'Selang Baja',
            'Teapot',
            'Wajan',
            'User',
            'Nama Lock',
            'PJ Admin Nota',
            'PJ Kolektor Nota',
            'Status Entry'
        ];
    }

    public function collection() {
        // $db = DB::select('select * from users');
        // return collect($db);
        return $this->data;
    }
}
