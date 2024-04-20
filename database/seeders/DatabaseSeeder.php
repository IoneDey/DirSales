<?php

namespace Database\Seeders;

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

        DB::table('users')->insert([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john.doe@example.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => 'Jane Smith',
            'username' => 'janesmith',
            'email' => 'jane.smith@example.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => 'Michael Johnson',
            'username' => 'michaelj',
            'email' => 'michael.johnson@example.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => 'Sarah Williams',
            'username' => 'sarahw',
            'email' => 'sarah.williams@example.com',
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => 'David Brown',
            'username' => 'davidb',
            'email' => 'david.brown@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->call(PTSeeder::class);
        $this->call(TimSeeder::class);
        $this->call(ProvinsiSeeder::class);
        $this->call(KotaSeeder::class);
        $this->call(BarangSeeder::class);

        // DB::table('pts')->insert([
        //     'kode' => '3P',
        //     'nama' => 'PT. TIGA PUTRA',
        //     'angsuranhari' => 4,
        //     'angsuranperiode' => 11,
        //     'userid' => 1,
        // ]);

        // DB::table('pts')->insert([
        //     'kode' => 'BK',
        //     'nama' => 'BINTANG KENCANA',
        //     'angsuranhari' => 7,
        //     'angsuranperiode' => 7,
        //     'userid' => 1,
        // ]);

        // DB::table('barangs')->insert([
        //     'kode' => 'SLBaja260',
        //     'nama' => 'Selang Baja',
        //     'userid' => 1,
        // ]);

        // DB::table('barangs')->insert([
        //     'kode' => 'SLBaja300',
        //     'nama' => 'Selang Baja',
        //     'userid' => 1,
        // ]);

        // DB::table('barangs')->insert([
        //     'kode' => 'SLBaja',
        //     'nama' => 'Selang Baja',
        //     'userid' => 1,
        // ]);

        // DB::table('barangs')->insert([
        //     'kode' => 'KP',
        //     'nama' => 'KIPAS',
        //     'userid' => 1,
        // ]);

        // DB::table('kotas')->insert([
        //     'provinsi' => 'PROVINSI 1',
        //     'kota_kabupaten' => 'KOTA KABUPATEN 11',
        // ]);

        // DB::table('kotas')->insert([
        //     'provinsi' => 'PROVINSI 1',
        //     'kota_kabupaten' => 'KOTA KABUPATEN 12',
        // ]);

        // DB::table('kotas')->insert([
        //     'provinsi' => 'PROVINSI 2',
        //     'kota_kabupaten' => 'KOTA KABUPATEN 21',
        // ]);

        // DB::table('kotas')->insert([
        //     'provinsi' => 'PROVINSI 2',
        //     'kota_kabupaten' => 'KOTA KABUPATEN 22',
        // ]);

        // DB::table('kotas')->insert([
        //     'provinsi' => 'PROVINSI 2',
        //     'kota_kabupaten' => 'KOTA KABUPATEN 23',
        // ]);

        // User::factory(10)->create();
        // Pts::factory(5)->create();
        // Barangs::factory(10)->create();
        // Timhd::factory(10)->create();
        // Timdt::factory(20)->create();
    }
}
