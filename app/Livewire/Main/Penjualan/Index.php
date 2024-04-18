<?php

namespace App\Livewire\Main\Penjualan;

use App\Models\Timdt as ModelsTimDt;
use App\Models\Timhd as ModelsTimHd;
use Livewire\Component;

class Index extends Component
{
    public $title = 'Main - Penjualan';

    //db
    public $dbTims;
    public $dbBarangTims;

    //entity
    public $idNomer;
    public $nomer;

    public function mount()
    {
        $this->dbTims = ModelsTimHd::orderby('created_at', 'desc')->get();
        $this->dbBarangTims = ModelsTimDt::where('nomerid', '=', '')->get();
    }

    public function updatedidNomer($id)
    {
        //dump($this->idNomer);
        $this->dbBarangTims = ModelsTimDt::where('nomerid', '=', $id)->get();
    }

    public function render()
    {
        return view('livewire.main.penjualan.index')->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
