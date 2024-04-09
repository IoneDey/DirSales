<?php

namespace App\Livewire\Panel\Pt;

use Livewire\Component;
use App\Models\Pts as ModelsPT;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $title = 'Panel - PT';

    public $kode;
    public $nama;
    public $angsuranhari;
    public $angsuranperiode;

    public function store()
    {
        $rules = [
            'kode' => ['required', 'min:3', 'max:10', 'unique:pts'],
            'nama' => ['required', 'min:5', 'max:255'],
            'angsuranhari' => ['required', 'integer', 'min:3', 'max:31'],
            'angsuranperiode' => ['required', 'integer', 'min:3', 'max:12']
        ];
        $pesan = [
            'kode.required' => 'Kode wajib diisi.',
            'nama.required' => 'Nama wajib diisi.',
            'angsuranhari.required' => 'Angsuran-Hari wajib diisi.',
            'angsuranperiode.required' => 'Angsuran-Peride wajib diisi.',
            'kode.min' => 'Kode min 3 karakter.',
            'nama.min' => 'Nama min 5 karakter.',
            'kode.max' => 'Kode maximum 10 karakter',
            'nama.max' => 'Nama maximum 255 karakter',
            'angsuranhari.min' => 'Angusran min 3',
            'angsuranperiode.min' => 'Angusran min 3',
            'angsuranhari.max' => 'Angusran max 31',
            'angsuranperiode.max' => 'Angusran max 12',
            'kode.unique' => 'Kode sudah ada didata.',
        ];


        $validatedData = $this->validate($rules, $pesan);
        $validatedData['userid'] = 1;
        //$validatedData['userid'] = auth()->user()->id;
        ModelsPT::create($validatedData);
        session()->flash('ok', 'Data ' . $this->kode . ' berhasil disimpan.');
    }


    public function render()
    {
        $data = ModelsPT::orderBy('updated_at', 'desc')->paginate(7);
        return view('livewire.panel.pt.index', [
            'dataPT' => $data,
        ])->layout('layouts.panel-layout', [
            'title' => $this->title,
        ]);
    }
}
