<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class Penagihan implements FromCollection, WithHeadings {
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $data;

    public function __construct(Collection $data) {
        $this->data = $data;
    }

    public function headings(): array {
        return [
            'Nama Tim',
            'Tanggal Dibuat',
            'Nota',
            'Nama Pelanggan',
            'Tanggal Penagihan',
            'Nama Penagih',
            'Foto Kwitansi',
            'Jumlah Bayar',
            'Biaya Komisi',
            'Biaya Admin',
            'Total'
        ];
    }

    public function collection() {
        //
        return $this->data;
    }
}
