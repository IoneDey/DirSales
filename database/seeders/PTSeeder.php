<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PTSeeder extends Seeder {

    /**
     * Run the database seeds.
     */
    public function run(): void {
        $data = [
            [
                'nama' => 'PT. Tiga Putra',
                'alamat' => 'Jalan Merdeka No. 10',
                'npwp' => '1234567890123456',
                'pkp' => 'Iya',
                'userid' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT. Bintang Kencana Pratama',
                'alamat' => 'Jalan Sudirman No. 25',
                'npwp' => '6543210987654321',
                'pkp' => 'Tidak',
                'userid' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT. Dinasty Singhasari Grup',
                'alamat' => 'Jalan Gatot Subroto No. 5',
                'npwp' => '9876543210123456',
                'pkp' => 'Tidak',
                'userid' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Masukkan data ke dalam tabel pts
        DB::table('pts')->insert($data);
    }
}
