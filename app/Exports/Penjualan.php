<?php

namespace App\Exports;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Penjualan implements FromCollection, WithHeadings {
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $data;

    public function __construct(Collection $data) {
        $this->data = $data;
    }

    public function headings(): array {
        return [
            'Tanggal Dibuat',
            'Tanggal Penjualan',
            'Nama Pelanggan',
            'Nama Barang',
            'Jumlah',
            'Telepon Pelanggan',
            'Nota',
            'Nama Sales',
            'Alamat Pelanggan',
            'Foto Nota',
            'Foto Nota Rekap',
            'Kecamatan',
            'Kota',
            'Periode Angsuran'
        ];
    }

    public function collection() {
        // $db = DB::select('select * from users');
        // return collect($db);
        return $this->data;
    }
}
