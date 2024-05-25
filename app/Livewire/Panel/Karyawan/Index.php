<?php

namespace App\Livewire\Panel\Karyawan;

use App\Models\Karyawan;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component {
    use WithPagination;
    public $title = 'Master Karyawan';

    //--field
    public $nik;
    public $nama;
    public $notelp = '';
    public $flagdriver = false;
    public $flagkolektor = false;

    //--end field + validation set

    //--cari + paginate
    public $cari;
    protected $paginationTheme = 'bootstrap';
    public function paginationView() {
        return 'vendor.livewire.bootstrap';
    }
    public function updatedcari() {
        $this->resetPage();
    }
    //--end cari + paginate

    //--sort
    public $sortColumn = "nama";
    public $sortDirection = "asc";
    //--end sort

    public $isUpdate = false;
    public $tmpId = null;

    public function clear() {
        $this->nik = "";
        $this->nama = "";
        $this->notelp = "";
        $this->flagdriver = false;
        $this->flagkolektor = false;
        $this->isUpdate = false;
        $this->tmpId = null;
    }

    public function getData($id) {
        if ($id != "") {
            $data = Karyawan::find($id);

            $this->nik = $data->nik;
            $this->nama = $data->nama;
            $this->notelp = $data->notelp;
            $this->flagdriver = (bool) $data->flagdriver;
            $this->flagkolektor = (bool) $data->flagkolektor;

            $this->isUpdate = true;
            $this->tmpId = $id;
        }
    }

    protected $messages = [
        'nik.required' => 'nik wajib diisi.',
        'nik.min' => 'nik minimal harus 5 karakter.',
        'nik.max' => 'nik tidak boleh lebih dari 10 karakter.',
        'nik.unique' => 'nik sudah dipakai.',
        'nama.required' => 'nama wajib diisi.',
        'nama.min' => 'nama minimal harus 3 karakter.',
        'nama.max' => 'nama tidak boleh lebih dari 255 karakter.',
        'notelp.min' => 'nomor telp minimal harus 10 digit.',
        'notelp.max' => 'nomer telp tidak boleh lebih dari 20 digit.',
        'flagdriver.bollean' => 'status true/false',
        'flagkolektor.bollean' => 'status true/false',
    ];

    public function create() {
        $rules = [
            'nik' => 'required|min:5|max:10|unique:karyawans,nik',
            'nama' => 'required|min:3|max:255',
            'notelp' => 'string|min:10|max:20',
            'flagdriver' => 'boolean',
            'flagkolektor' => 'boolean',
        ];

        $validatedData = $this->validate($rules, $this->messages);
        $validatedData['userid'] = auth()->user()->id;

        Karyawan::create($validatedData);
        $msg = 'Tambah data ' . $this->nama . ' berhasil.';
        $this->clear();
        session()->flash('ok', $msg);
    }

    public function edit($id) {
        $this->getData($id);
    }

    public function update() {
        if ($this->tmpId) {
            $data = Karyawan::find($this->tmpId);

            $rules = [
                'nama' => 'required|min:3|max:255',
                'notelp' => 'string|min:10|max:20',
                'flagdriver' => 'boolean',
                'flagkolektor' => 'boolean',
            ];
            if (($this->nik != $data->nik)) {
                $rules['nik'] = ['required', 'min:5', 'max:10', 'unique:karyawans,nik'];
            }
            $validatedData = $this->validate($rules, $this->messages);

            try {
                $validatedData['userid'] = auth()->user()->id;
                $data->update($validatedData);

                $msg = 'Update data ' . $this->nama . ' berhasil.';
                $this->clear();
                session()->flash('ok', $msg);
            } catch (\Exception $e) {
                $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.', '(' . $e->getMessage() . ')'));
                session()->flash('error', $errors);
            }
        }
    }

    public function confirmDelete($id) {
        $this->getData($id);
    }

    public function delete() {
        if ($this->tmpId) {
            $data = Karyawan::find($this->tmpId);
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

    public function sort($column) {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function render() {
        $data = Karyawan::where(function ($query) {
            $query->Where('nama', 'like', '%' . $this->cari . '%');
        })
            ->orderby($this->sortColumn, $this->sortDirection)
            ->paginate(12);

        return view('livewire.panel.karyawan.index', [
            'datas' => $data,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.panel',
            'title' => $this->title,
        ]);
    }
}
