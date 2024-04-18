<?php

namespace App\Livewire\Panel\Barang;

use App\Models\Barangs as ModelsBarang;
use Livewire\Component;
use Livewire\WithPagination;
use PhpParser\Node\Stmt\TryCatch;

class Index extends Component
{
    use WithPagination;
    public $title = 'Panel - Barang';

    //untuk deklaras filed2x database
    public $kode;
    public $nama;

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
        ];
        $pesan = [
            'kode.required' => 'Kode wajib diisi.',
            'kode.min' => 'Kode min 3 karakter.',
            'kode.max' => 'Kode maximum 10 karakter',
            'kode.unique' => 'Kode sudah ada didata.',
            'nama.required' => 'Nama wajib diisi.',
            'nama.min' => 'Nama min 5 karakter.',
            'nama.max' => 'Nama maximum 255 karakter',
        ];

        $validatedData = $this->validate($rules, $pesan);
        $validatedData['userid'] = auth()->user()->id;

        ModelsBarang::create($validatedData);
        session()->flash('ok', 'Data ' . $this->kode . ' berhasil disimpan.');
        $this->clear();
    }

    public function edit($id)
    {
        $data = ModelsBarang::find($id);
        $this->kode = $data->kode;
        $this->nama = $data->nama;

        $this->isUpdate = true;
        $this->temp_id = $id;
    }

    public function update()
    {
        $data = ModelsBarang::find($this->temp_id);

        $rules = [
            'nama' => ['required', 'min:5', 'max:255'],
        ];
        $pesan = [
            'nama.required' => 'Nama wajib diisi.',
            'nama.min' => 'Nama min 5 karakter.',
            'nama.max' => 'Nama maximum 255 karakter',
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
        session()->flash('ok', 'Data ' . $this->kode . ' berhasil di-update.');

        $this->clear();
    }

    public function delete_confirm($id)
    {
        if ($id != "") {
            $data = ModelsBarang::find($id);
            $this->kode = $data->kode;
            $this->nama = $data->nama;

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
                $data = ModelsBarang::find($id);
                $data->delete();
                $msg = 'Data ' . $this->kode . ' berhasil di-delete.';
            } else {
                if (count($this->selected_id)) {
                    for ($x = 0; $x < count($this->selected_id); $x++) {
                        $data = ModelsBarang::find($this->selected_id[$x]);
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
            $data = ModelsBarang::where('kode', 'like', '%' . $this->textcari . '%')
                ->orWhere('nama', 'like', '%' . $this->textcari . '%')
                ->orderBy($this->sortColumn, $this->sortDirection)->paginate(7);
        } else {
            $data = ModelsBarang::orderBy($this->sortColumn, $this->sortDirection)->paginate(7);
        }

        return view('livewire.panel.barang.index', [
            'dataBarang' => $data,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.panel',
            'title' => $this->title,
        ]);
    }
}
