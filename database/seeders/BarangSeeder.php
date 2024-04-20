<?php

namespace Database\Seeders;

use App\Models\Barang;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            Barang::create([
                'nama' => 'Barang ' . $i,
                'userid' => 1, // Contoh acak userid dari 1-10
            ]);
        }
    }
}
