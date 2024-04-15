<?php

namespace App\Livewire\Panel\Pt;

use App\Models\Pts as ModelsPT;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $title = 'Panel - PT';

    //untuk deklaras filed2x database
    public $kode;
    public $nama;
    public $angsuranhari;
    public $angsuranperiode;

    //untuk status create datau edit
    public $isUpdate = false;

    //untuk delete row atau bulk
    public $temp_id;
    public $selectedCount = 0;
    public $selected_id = [];

    //untuk multi pencarian
    public $textcari;

    //untuk sort
    public $sortColumn = "kode";
    public $sortDirection = "asc";

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
        $this->selectedCount = 0;
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
        $validatedData['userid'] = auth()->user()->id;
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
        $validatedData['userid'] = auth()->user()->id;

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

            $this->isUpdate = true;
            $this->temp_id = $id;
        } else {
            $this->selectedCount = count($this->selected_id);
        }
    }

    public function delete()
    {
        try {

            $id = $this->temp_id;
            if ($id != '') {
                $data = ModelsPT::find($id);
                $data->delete();
                $msg = 'Data ' . $this->kode . ' berhasil di-delete.';
            } else {
                if (count($this->selected_id)) {
                    for ($x = 0; $x < count($this->selected_id); $x++) {
                        $data = ModelsPT::find($this->selected_id[$x]);
                        $data->delete();
                    }
                    $msg = 'Berhasil hapus ' . $this->selectedCount . ' data.';
                }
            }
            session()->flash('ok', $msg);
            $this->clear();
        } catch (\Exception $e) {

            $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.'));
            session()->flash('error', $errors);
        }
    }

    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        if ($this->textcari != null) {
            $data = ModelsPT::where('kode', 'like', '%' . $this->textcari . '%')
                ->orWhere('nama', 'like', '%' . $this->textcari . '%')
                ->orWhere('angsuranhari', '=', $this->textcari)
                ->orWhere('angsuranperiode', '=', $this->textcari)
                ->orderBy($this->sortColumn, $this->sortDirection)->paginate(15);
        } else {
            $data = ModelsPT::orderBy($this->sortColumn, $this->sortDirection)->paginate(15);
        }

        return view('livewire.panel.pt.index', [
            'dataPT' => $data,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.panel',
            'title' => $this->title,
        ]);
    }
}