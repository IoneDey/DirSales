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
    Route::get('/panel/sales', App\Livewire\Panel\Sales\Index::class)->name('sales');
    Route::get('/panel/karyawan', App\Livewire\Panel\Karyawan\Index::class)->name('karyawan');
    Route::get('/panel/tim', App\Livewire\Panel\Tim\Index::class)->name('tim');
    Route::get('/panel/provinsi', App\Livewire\Panel\Provinsi\Index::class)->name('provinsi');
    Route::get('/panel/kota', App\Livewire\Panel\Kota\Index::class)->name('kota');
    Route::get('/panel/barang', App\Livewire\Panel\Barang\Index::class)->name('barang');
    Route::get('/panel/user', App\Livewire\Panel\User\Index::class)->name('user');

    Route::get('/panel/timsetup', App\Livewire\Panel\Timsetup\Index::class)->name('timsetup');
    Route::get('/panel/piutangkartu', App\Livewire\Panel\Piutang\Kartu::class)->name('piutangkartu');

    //Route::get('/main/penjualan', App\Livewire\Main\Penjualan\Index::class)->name('penjualan');
});

Route::middleware(['checkroles:SUPERVISOR,ADMIN 1,ADMIN 2'])->group(function () {
    Route::get('/main/penjualan', App\Livewire\Main\Penjualan\Index::class)->name('penjualan');
    Route::get('/main/penjualanret', App\Livewire\Main\Penjualan\Retur::class)->name('penjualanret');
});

Route::middleware(['checkroles:SUPERVISOR,ADMIN 1,LOCK'])->group(function () {
    Route::get('/main/penjualanvalidasi', App\Livewire\Main\Penjualan\Validasi::class)->name('penjualanvalidasi');
    Route::get('/main/penjualanvalidasiedit/{id}', App\Livewire\Main\Penjualan\Validasiedit::class)->name('penjualanvalidasiedit');
});

Route::middleware(['checkroles:SUPERVISOR,ADMIN 1,ADMIN 2,LOCK'])->group(function () {
    Route::get('/main/penjualanreport', App\Livewire\Main\Penjualan\Laporan::class)->name('penjualanreport');
});

Route::middleware(['checkroles:SUPERVISOR,PENAGIHAN'])->group(function () {
    Route::get('/main/penagihan', App\Livewire\Main\Penagihan\index::class)->name('penagihan');
});

route::middleware('auth')->group(function () {
    Route::get('/profile', App\Livewire\Profile::class)->name('profile');
    Route::get('/logout', [App\Livewire\Logout::class, 'logout'])->name('logout');
});

Route::get('/login', App\Livewire\Login::class)->name('login')->middleware('guest');
Route::get('/', App\Livewire\Main\Index::class)->name('main');
