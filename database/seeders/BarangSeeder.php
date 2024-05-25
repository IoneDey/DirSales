<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $data = [
            'Selang 4 Lapis',
            'Selang Baja',
            'Kerudung',
            'Seal Clamp',
            'Presto',
            'Wajan',
            'Regulator Tectum',
            'Kipas',
            'Teapot'
        ];

        foreach ($data as $nama) {
            Barang::create([
                'nama' => $nama,
                'userid' => 1 // Isi user_id dengan nilai 1
            ]);
        }
    }
}
