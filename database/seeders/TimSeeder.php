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
                'nama' => 'Tim A',
                'ptid' => 1, // ID dari PT yang sudah ada di tabel 'pts'
                'userid' => 1, // ID dari user yang sudah ada di tabel 'users'
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tim B',
                'ptid' => 2,
                'userid' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tim C',
                'ptid' => 3,
                'userid' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tim D',
                'ptid' => 4,
                'userid' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Tim E',
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
