<?php

namespace App\Livewire\Panel\Provinsi;

use App\Models\Provinsi;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $title = 'Master Provinsi';

    //--field + validation set
    #[Rule('required|min:3|max:255|unique:provinsis')]
    public $nama;

    protected $messages = [
        'nama.required' => 'nama wajib diisi.',
        'nama.min' => 'nama minimal harus 3 karakter.',
        'nama.max' => 'nama tidak boleh lebih dari 255 karakter.',
        'nama.unique' => 'nama sudah dipakai.',
    ];
    //--end field + validation set

    //--cari + paginate
    public $cari;
    protected $paginationTheme = 'bootstrap';
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    public function updatedcari()
    {
        $this->resetPage();
    }
    //--end cari + paginate

    //--sort
    public $sortColumn = "nama";
    public $sortDirection = "asc";
    //--end sort

    public $isUpdate = false;
    public $tmpId = null;

    public function clear()
    {
        $this->nama = "";
        $this->isUpdate = false;
        $this->tmpId = null;
    }

    public function getDataProvinsi($id)
    {
        if ($id != "") {
            $data = Provinsi::find($id);

            $this->nama = $data->nama;

            $this->isUpdate = true;
            $this->tmpId = $id;
        }
    }

    public function create()
    {
        $validatedData = $this->validate();
        $validatedData['userid'] = auth()->user()->id;

        Provinsi::create($validatedData);
        $msg = 'Tambah data ' . $this->nama . ' berhasil.';
        $this->clear();
        session()->flash('ok', $msg);
    }

    public function edit($id)
    {
        $this->getDataProvinsi($id);
    }

    public function update()
    {
        if ($this->tmpId) {
            $data = Provinsi::find($this->tmpId);

            if ($this->nama != $data->nama) {
                $rules['nama'] = 'required|min:3|max:255|unique:provinsi';
            }

            try {
                $validatedData = $this->validate($rules);
                $validatedData['userid'] = auth()->user()->id;
                $data->update($validatedData);

                $msg = 'Update data ' . $this->nama . ' berhasil.';
                $this->clear();
                session()->flash('ok', $msg);
            } catch (\Exception $e) {
                $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.', $e->getMessage()));
                session()->flash('error', $errors);
            }
        }
    }

    public function confirmDelete($id)
    {
        $this->getDataProvinsi($id);
    }

    public function delete()
    {
        if ($this->tmpId) {
            $data = Provinsi::find($this->tmpId);
            $msg = 'Data ' . $this->nama . ' berhasil dihapus.';
            try {
                $data->delete();
                $this->clear();
                session()->flash('ok', $msg);
            } catch (\Exception $e) {
                $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.'));
                session()->flash('error', $errors);
            }
            //$this->js('alert("$this->msg")');
        }
    }

    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        $data = Provinsi::where('nama', 'like', '%' . $this->cari . '%')
            ->orderby($this->sortColumn, $this->sortDirection)
            ->paginate(12);

        return view('livewire.panel.provinsi.index', [
            'datas' => $data,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.panel',
            'title' => $this->title,
        ]);
    }
}
