<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Pts;
use App\Models\Barangs;
use App\Models\Timhd;
use App\Models\Kotas;
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

        DB::table('barangs')->insert([
            'kode' => 'SLBaja260',
            'nama' => 'Selang Baja',
            'userid' => 1,
        ]);

        DB::table('barangs')->insert([
            'kode' => 'SLBaja300',
            'nama' => 'Selang Baja',
            'userid' => 1,
        ]);

        DB::table('barangs')->insert([
            'kode' => 'SLBaja',
            'nama' => 'Selang Baja',
            'userid' => 1,
        ]);

        DB::table('barangs')->insert([
            'kode' => 'KP',
            'nama' => 'KIPAS',
            'userid' => 1,
        ]);

        DB::table('kotas')->insert([
            'provinsi' => 'PROVINSI',
            'kota_kabupaten' => 'KOTA KABUPATEN 1',
        ]);

        DB::table('kotas')->insert([
            'provinsi' => 'PROVINSI',
            'kota_kabupaten' => 'KOTA KABUPATEN 2',
        ]);

        User::factory(10)->create();
        Pts::factory(5)->create();
        Barangs::factory(10)->create();
        Timhd::factory(5)->create();
    }
}
