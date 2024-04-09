<?php

namespace Database\Seeders;

use App\Models\Users;
use App\Models\Pts;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Dedy Setiawan',
            'username' => 'DedyS',
            'email' => 'ione.dey@gmail.com',
            'password' => Hash::make('12345'),
        ]);

        DB::table('users')->insert([
            'name' => 'Demo',
            'username' => 'demo',
            'email' => 'demo@gmail.com',
            'password' => Hash::make('Aa.Bb.Cc'),
        ]);

        DB::table('pts')->insert([
            'kode' => '3P',
            'nama' => 'PT. TIGA PUTRA',
            'angsuranhari' => 4,
            'angsuranperiode' => 11,
            'userid' => 1,
        ]);

        DB::table('pts')->insert([
            'kode' => 'BK',
            'nama' => 'BINTANG KENCANA',
            'angsuranhari' => 7,
            'angsuranperiode' => 7,
            'userid' => 1,
        ]);

        Users::factory(10)->create();
        Pts::factory(5)->create();
    }
}
