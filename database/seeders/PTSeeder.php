<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PTSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'PT Amanah Jaya',
                'alamat' => 'Jalan Merdeka No. 10',
                'npwp' => '1234567890123456',
                'pkp' => 'Iya',
                'userid' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Berkah Makmur',
                'alamat' => 'Jalan Sudirman No. 25',
                'npwp' => '6543210987654321',
                'pkp' => 'Tidak',
                'userid' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Cipta Sejahtera',
                'alamat' => 'Jalan Gatot Subroto No. 5',
                'npwp' => '9876543210123456',
                'pkp' => 'Tidak',
                'userid' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Damai Sentosa',
                'alamat' => 'Jalan Pahlawan No. 15',
                'npwp' => '5678901234567890',
                'pkp' => 'Iya',
                'userid' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'PT Emas Mulia',
                'alamat' => 'Jalan Hayam Wuruk No. 30',
                'npwp' => '3456789012345678',
                'pkp' => 'Iya',
                'userid' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Masukkan data ke dalam tabel pts
        DB::table('pts')->insert($data);
    }
}
