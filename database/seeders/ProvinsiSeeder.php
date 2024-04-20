<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinsi = [
            ['nama' => 'Bali', 'userid' => 1],
            ['nama' => 'Bangka Belitung', 'userid' => 1],
            ['nama' => 'Banten', 'userid' => 1],
            ['nama' => 'Bengkulu', 'userid' => 1],
            ['nama' => 'Daerah Istimewa Yogyakarta', 'userid' => 1],
            ['nama' => 'DKI Jakarta', 'userid' => 1],
            ['nama' => 'Gorontalo', 'userid' => 1],
            ['nama' => 'Jambi', 'userid' => 1],
            ['nama' => 'Jawa Barat', 'userid' => 1],
            ['nama' => 'Jawa Tengah', 'userid' => 1],
            ['nama' => 'Jawa Timur', 'userid' => 1],
            ['nama' => 'Kalimantan Barat', 'userid' => 1],
            ['nama' => 'Kalimantan Selatan', 'userid' => 1],
            ['nama' => 'Kalimantan Tengah', 'userid' => 1],
            ['nama' => 'Kalimantan Timur', 'userid' => 1],
            ['nama' => 'Kalimantan Utara', 'userid' => 1],
            ['nama' => 'Kepulauan Riau', 'userid' => 1],
            ['nama' => 'Lampung', 'userid' => 1],
            ['nama' => 'Maluku', 'userid' => 1],
            ['nama' => 'Maluku Utara', 'userid' => 1],
            ['nama' => 'NAD Aceh', 'userid' => 1],
            ['nama' => 'Nusa Tenggara Barat', 'userid' => 1],
            ['nama' => 'Nusa Tenggara Timur', 'userid' => 1],
            ['nama' => 'Papua', 'userid' => 1],
            ['nama' => 'Papua Barat', 'userid' => 1],
            ['nama' => 'Papua Barat Daya', 'userid' => 1],
            ['nama' => 'Papua Pegunungan', 'userid' => 1],
            ['nama' => 'Papua Selatan', 'userid' => 1],
            ['nama' => 'Papua Tengah', 'userid' => 1],
            ['nama' => 'Riau', 'userid' => 1],
            ['nama' => 'Sulawesi Barat', 'userid' => 1],
            ['nama' => 'Sulawesi Selatan', 'userid' => 1],
            ['nama' => 'Sulawesi Tengah', 'userid' => 1],
            ['nama' => 'Sulawesi Tenggara', 'userid' => 1],
            ['nama' => 'Sulawesi Utara', 'userid' => 1],
            ['nama' => 'Sumatera Barat', 'userid' => 1],
            ['nama' => 'Sumatera Selatan', 'userid' => 1],
            ['nama' => 'Sumatera Utara', 'userid' => 1],
        ];

        DB::table('provinsis')->insert($provinsi);
    }
}
