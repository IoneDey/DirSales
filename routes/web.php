<?php

use App\Http\Controllers\PtController;
use Illuminate\Support\Facades\Route;

/*
Route::get('/', function () {
    return view('welcome');
});
*/

//Route::get('/', App\Livewire\Logout::class);

route::middleware('auth')->group(function () {
    Route::get('/panel', App\Livewire\Panel\Index::class)->name('panel');
    Route::get('/panel/pt', App\Livewire\Panel\Pt\Index::class)->name('pt');
    Route::get('/panel/barang', App\Livewire\Panel\Barang\Index::class)->name('barang');
    Route::get('/panel/kota', App\Livewire\Panel\Kota\Index::class)->name('kota');
    Route::get('/panel/tim', App\Livewire\Panel\Tim\Index::class)->name('tim');
    Route::get('/logout', [App\Livewire\Logout::class, 'logout'])->name('logout');

    Route::get('/main/penjualan', App\Livewire\Main\Penjualan\Index::class)->name('penjualan');
});

Route::get('/login', App\Livewire\Login::class)->name('login')->middleware('guest');
Route::get('/', App\Livewire\Main\Index::class)->name('main');
