<?php

namespace App\Livewire\Panel\Tim;

use App\Models\Barangs as ModelsBarang;
use App\Models\Kotas as ModelsKota;
use App\Models\Pts as ModelsPt;
use App\Models\Timhd as ModelsTimHd;
use DateTime;
use Illuminate\Support\Facades\Date;
use Livewire\Component;
use Livewire\WithPagination;
use Ramsey\Uuid\Type\Integer;

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
    public $dataBarangDetail = [];

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
        $this->dataBarangDetail = [];
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

        $this->dataBarangDetail = [];
        $i = 0;
        foreach ($data->joinTimdt as $timdt) {
            $item = [
                'barangid' => $timdt->barangid,
                'hpp' => $timdt->hpp,
                'hargajual' => $timdt->hargajual
            ];
            $this->dataBarangDetail[] = $item;
        }
        //dd($data->joinTimdt->count());
    }

    public function addDataArray()
    {
        $item = [
            'barangid' => '',
            'hpp' => '',
            'hargajual' => ''
        ];
        $this->dataBarangDetail[] = $item;
    }

    public function delDataArray($index)
    {
        unset($this->dataBarangDetail[$index]);
        $this->dataBarangDetail = array_values($this->dataBarangDetail);
    }

    public function render()
    {
        $dbTimHd = ModelsTimHd::orderBy('tglakhir', 'desc')->paginate(5);
        $dbPT = ModelsPt::orderBy('nama', 'asc')->get();
        $dbKota = ModelsKota::orderBy('kota_kabupaten', 'asc')->get();
        $dbBarang = ModelsBarang::orderBy('nama', 'asc')->get();

        return view('livewire.panel.tim.index', [
            'timhdLists' => $dbTimHd,
            'ptLists' => $dbPT,
            'KotaLists' => $dbKota,
            'BarangLists' => $dbBarang,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.panel',
            'title' => $this->title,
        ]);
    }
}
