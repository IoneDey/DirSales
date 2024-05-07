<?php

use App\Http\Middleware\Checkroles;
use Illuminate\Support\Facades\Route;

/*
Route::get('/', function () {
    return view('welcome');
});
*/

//Route::get('/', App\Livewire\Logout::class);

Route::middleware(['checkroles:SUPERVISOR'])->group(function () {
    Route::get('/panel', App\Livewire\Panel\Index::class)->name('panel');
    Route::get('/panel/pt', App\Livewire\Panel\Pt\Index::class)->name('pt');
    Route::get('/panel/tim', App\Livewire\Panel\Tim\Index::class)->name('tim');
    Route::get('/panel/provinsi', App\Livewire\Panel\Provinsi\Index::class)->name('provinsi');
    Route::get('/panel/kota', App\Livewire\Panel\Kota\Index::class)->name('kota');
    Route::get('/panel/barang', App\Livewire\Panel\Barang\Index::class)->name('barang');
    Route::get('/panel/user', App\Livewire\Panel\User\Index::class)->name('user');

    Route::get('/panel/timsetup', App\Livewire\Panel\Timsetup\Index::class)->name('timsetup');

    //Route::get('/main/penjualan', App\Livewire\Main\Penjualan\Index::class)->name('penjualan');
});

Route::middleware(['checkroles:SUPERVISOR,SPV ADMIN,ADMIN'])->group(function () {
    Route::get('/main/penjualan', App\Livewire\Main\Penjualan\Index::class)->name('penjualan');
});

Route::middleware(['checkroles:SUPERVISOR,SPV ADMIN,SPV LOCK,LOCK'])->group(function () {
    Route::get('/main/penjualanvalidasi', App\Livewire\Main\Penjualan\Validasi::class)->name('penjualanvalidasi');
});

Route::middleware(['checkroles:SUPERVISOR,SPV ADMIN,ADMIN,SPV LOCK,LOCK'])->group(function () {
    Route::get('/main/penjualanreport', App\Livewire\Main\Penjualan\Laporan::class)->name('penjualanreport');
});

route::middleware('auth')->group(function () {
    Route::get('/logout', [App\Livewire\Logout::class, 'logout'])->name('logout');
});

Route::get('/login', App\Livewire\Login::class)->name('login')->middleware('guest');
Route::get('/', App\Livewire\Main\Index::class)->name('main');
