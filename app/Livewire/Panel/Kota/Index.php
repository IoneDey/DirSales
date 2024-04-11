<?php

namespace App\Livewire\Panel\Kota;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kotas as ModelKota;

class Index extends Component
{
    use WithPagination;
    public $title = 'Panel - Daftar Kota';

    //untuk multi pencarian
    public $textcari;

    //untuk sort
    public $sortColumn = "provinsi";
    public $sortDirection = "asc";

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        if ($this->textcari != null) {
            $data = ModelKota::where('provinsi', 'like', '%' . $this->textcari . '%')
                ->orWhere('kota_kabupaten', 'like', '%' . $this->textcari . '%')
                ->orderBy($this->sortColumn, $this->sortDirection)->paginate(10);
        } else {
            $data = ModelKota::orderBy($this->sortColumn, $this->sortDirection)->paginate(10);
        }

        return view('livewire.panel.kota.index', [
            'dataKota' => $data,
        ])
            ->layout('layouts.panel-layout', [
                'title' => $this->title,
            ]);
    }
}
