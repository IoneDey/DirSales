<?php

namespace App\Livewire\Panel\Tim;

use App\Models\Timhd as ModelsTimHd;
use DateTime;
use Illuminate\Support\Facades\Date;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $title = 'Panel - Buat Tim';
    //data list timhd
    public $dbTimHd;

    //entity
    public $tim;
    public $ptid;
    public $kotaid;
    public $tglawal;
    public $tglakhir;
    public string $pic;

    //data array barang
    public $dataDetail = [];

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function clear()
    {
        $this->tim = "";
        $this->ptid = "";
        $this->kotaid = "";
        $this->tglawal = "";
        $this->tglakhir = "";
        $this->pic = "";
        $this->dataDetail = [];
    }

    public function store()
    {
        $this->validate([
            'tim' => 'required', // Atur aturan validasi sesuai kebutuhan
        ]);

        dd($this);
    }

    public function edit($id)
    {
        $data = ModelsTimHd::find($id);
        $this->tim = $data->nomer;
        $this->ptid = $data->ptid;
        $this->kotaid = $data->kotaid;
        $this->tglawal = $data->tglawal;
        $this->tglakhir = $data->tglakhir;
        $this->pic = $data->pic;
    }

    public function addDataArray()
    {
        $this->dataDetail[] = ['', '', ''];
    }

    public function delDataArray($index)
    {
        unset($this->dataDetail[$index]);
        $this->dataDetail = array_values($this->dataDetail);
    }

    public function render()
    {
        $dbTimHd = ModelsTimHd::orderBy('tglakhir', 'desc')->paginate(5);

        return view('livewire.panel.tim.index', [
            'timhdLists' => $dbTimHd,
        ])->layout('layouts.panel-layout', [
            'title' => $this->title,
        ]);
    }
}
