<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'nama' => 'Tim 1',
                'ptid' => 1, // ID dari PT yang sudah ada di tabel 'pts'
                'userid' => 1, // ID dari user yang sudah ada di tabel 'users'
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tim 2',
                'ptid' => 2,
                'userid' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tim 3',
                'ptid' => 3,
                'userid' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tim 4',
                'ptid' => 4,
                'userid' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tim 5',
                'ptid' => 5,
                'userid' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Masukkan data ke dalam tabel tims
        DB::table('tims')->insert($data);
    }
}
