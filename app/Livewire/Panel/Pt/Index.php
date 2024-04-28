<?php

namespace App\Livewire\Panel\Pt;

use App\Models\Pt;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $title = 'Master PT';

    //--field + validation set
    #[Rule('required|min:3|max:255|unique:pts')]
    public $nama;
    #[Rule('required|min:3|max:255')]
    public $alamat;
    #[Rule('max:16')]
    public $npwp;
    #[Rule('required')]
    public $pkp;

    protected $messages = [
        'nama.required' => 'nama wajib diisi.',
        'nama.min' => 'nama minimal harus 3 karakter.',
        'nama.max' => 'nama tidak boleh lebih dari 255 karakter.',
        'nama.unique' => 'nama sudah dipakai.',
        'alamat.required' => 'alamat wajib diisi.',
        'alamat.min' => 'nama minimal harus 3 karakter.',
        'alamat.max' => 'nama tidak boleh lebih dari 255 karakter.',
        'npwp.max' => 'npwp tidak boleh lebih dari 16 karakter.',
        'pkp.required' => 'pkp wajib diisi.',
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
        $this->alamat = "";
        $this->npwp = "";
        $this->pkp = "";
        $this->isUpdate = false;
        $this->tmpId = null;
    }

    public function getDataPT($id)
    {
        if ($id != "") {
            $data = Pt::find($id);

            $this->nama = $data->nama;
            $this->alamat = $data->alamat;
            $this->npwp = $data->npwp;
            $this->pkp = $data->pkp;

            $this->isUpdate = true;
            $this->tmpId = $id;
        }
    }

    public function create()
    {
        $validatedData = $this->validate();
        $validatedData['userid'] = auth()->user()->id;

        Pt::create($validatedData);
        $msg = 'Tambah data ' . $this->nama . ' berhasil.';
        $this->clear();
        session()->flash('ok', $msg);
    }

    public function edit($id)
    {
        $this->getDataPT($id);
    }

    public function update()
    {
        if ($this->tmpId) {
            $data = Pt::find($this->tmpId);

            $rules = [
                'alamat' => 'required|min:3|max:255',
                'npwp' => 'max:16',
                'pkp' => 'required',
            ];

            if ($this->nama != $data->nama) {
                $rules['nama'] = 'required|min:3|max:255|unique:pts';
            }

            try {
                $validatedData = $this->validate($rules);
                $validatedData['userid'] = auth()->user()->id;
                $data->update($validatedData);

                $msg = 'Update data ' . $this->nama . ' berhasil.';
                $this->clear();
                session()->flash('ok', $msg);
            } catch (\Exception $e) {
                $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.'));
                session()->flash('error', $errors);
            }
        }
    }

    public function confirmDelete($id)
    {
        $this->getDataPT($id);
    }

    public function delete()
    {
        if ($this->tmpId) {
            $data = Pt::find($this->tmpId);
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
        $data = Pt::where('nama', 'like', '%' . $this->cari . '%')
            ->orWhere('alamat', 'like', '%' . $this->cari . '%')
            ->orWhere('npwp', 'like', '%' . $this->cari . '%')
            ->orWhere('pkp', 'like', '%' . $this->cari . '%')
            ->orderby($this->sortColumn, $this->sortDirection)
            ->paginate(12);

        return view('livewire.panel.pt.index', [
            'datas' => $data,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.panel',
            'title' => $this->title,
        ]);
    }
}
