<?php

use Illuminate\Support\Facades\Route;


/*
Route::get('/', function () {
    return view('welcome');
});
*/


Route::get('/', App\Livewire\Main\Index::class)->name('main');


Route::get('/panel', App\Livewire\Panel\Index::class)->name('panel');
Route::get('/pt', App\Livewire\Panel\Pt\Index::class)->name('pt');
Route::get('/barang', App\Livewire\Panel\Barang\Index::class)->name('barang');
