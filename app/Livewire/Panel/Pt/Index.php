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

    public $isUpdate = false;
    public $temp_id;

    public $textcari;
    public $selected_id = [];

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }

    public function clear()
    {
        $this->kode = "";
        $this->nama = "";
        $this->angsuranhari = "";
        $this->angsuranperiode = "";
        $this->isUpdate = false;
        $this->temp_id = "";
        $this->selected_id = [];
    }

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
        $this->clear();
    }


    public function edit($id)
    {
        $data = ModelsPT::find($id);
        $this->kode = $data->kode;
        $this->nama = $data->nama;
        $this->angsuranhari = $data->angsuranhari;
        $this->angsuranperiode = $data->angsuranperiode;

        $this->isUpdate = true;
        $this->temp_id = $id;
    }

    public function update()
    {
        $data = ModelsPT::find($this->temp_id);

        $rules = [
            'nama' => ['required', 'min:5', 'max:255'],
            'angsuranhari' => ['required', 'integer', 'min:3', 'max:31'],
            'angsuranperiode' => ['required', 'integer', 'min:3', 'max:12']
        ];
        $pesan = [
            'nama.required' => 'Nama wajib diisi.',
            'nama.min' => 'Nama min 5 karakter.',
            'nama.max' => 'Nama maximum 255 karakter',
            'angsuranhari.required' => 'Angsuran-Hari wajib diisi.',
            'angsuranhari.min' => 'Angusran min 3',
            'angsuranhari.max' => 'Angusran max 31',
            'angsuranperiode.required' => 'Angsuran-Peride wajib diisi.',
            'angsuranperiode.min' => 'Angusran min 3',
            'angsuranperiode.max' => 'Angusran max 12',
        ];

        if ($data->kode != $this->kode) {
            $rules = ['kode' => ['required', 'min:3', 'max:10', 'unique:pts']];
            $pesan = [
                'kode.required' => 'Kode wajib diisi.',
                'kode.min' => 'Kode min 3 karakter.',
                'kode.max' => 'Kode maximum 10 karakter',
                'kode.unique' => 'Kode sudah ada didata.',
            ];
        }
        $validatedData = $this->validate($rules, $pesan);
        $validatedData['userid'] = 1;
        //$validatedData['userid'] = auth()->user()->id;

        $data->update($validatedData);
        //ModelsPT::create($validatedData);
        session()->flash('ok', 'Data ' . $this->kode . ' berhasil di-update.');

        $this->clear();
    }

    public function delete_confirm($id)
    {
        if ($id != "") {
            $data = ModelsPT::find($id);
            $this->kode = $data->kode;
            $this->nama = $data->nama;
            $this->angsuranhari = $data->angsuranhari;
            $this->angsuranperiode = $data->angsuranperiode;

            $this->temp_id = $id;
        }
    }

    public function delete()
    {
        $id = $this->temp_id;
        if ($id != '') {
            $data = ModelsPT::find($id);
            $data->delete();
        } else {
            if (count($this->selected_id)) {
                for ($x = 0; $x < count($this->selected_id); $x++) {
                    $data = ModelsPT::find($this->selected_id[$x]);
                    $data->delete();
                }
            }
        }
        session()->flash('ok', 'Data ' . $this->kode . ' berhasil di-delete.');
        $this->clear();
    }

    public function render()
    {
        if ($this->textcari != null) {
            $data = ModelsPT::where('kode', 'like', '%' . $this->textcari . '%')
                ->orWhere('nama', 'like', '%' . $this->textcari . '%')
                ->orWhere('angsuranhari', '=', $this->textcari)
                ->orWhere('angsuranperiode', '=', $this->textcari)
                ->orderBy('updated_at', 'desc')->paginate(7);
        } else {
            $data = ModelsPT::orderBy('updated_at', 'desc')->paginate(7);
        }

        return view('livewire.panel.pt.index', [
            'dataPT' => $data,
        ])->layout('layouts.panel-layout', [
            'title' => $this->title,
        ]);
    }
}
