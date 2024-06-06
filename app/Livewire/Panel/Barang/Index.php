<?php

namespace App\Livewire\Panel\Barang;

use App\Models\Barang;
use Livewire\Component;
use Livewire\WithPagination;
use PDF;

class Index extends Component {
    use WithPagination;
    public $title = 'Master Barang';

    //--field + validation set
    public $nama;
    public $kode;

    protected $messages = [
        'nama.required' => 'nama wajib diisi.',
        'nama.min' => 'nama minimal harus 3 karakter.',
        'nama.max' => 'nama tidak boleh lebih dari 255 karakter.',
        'nama.unique' => 'nama sudah dipakai.',
        'kode.min' => 'kode minimal harus 3 karakter.',
        'kode.max' => 'kode tidak boleh lebih dari 15 karakter.',
    ];
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
        $this->nama = "";
        $this->kode = "";
        $this->isUpdate = false;
        $this->tmpId = null;
    }

    public function exportPDF() {
        $title = 'Master Barang';
        $data = Barang::get();

        $pdf = PDF::loadView(
            'livewire/pdf/barangpdf',
            [
                'title' => $title,
                'datas' => $data
            ]
        );

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'document.pdf');
    }

    public function getDataBarang($id) {
        if ($id != "") {
            $data = Barang::find($id);

            $this->nama = $data->nama;
            $this->kode = $data->kode;

            $this->isUpdate = true;
            $this->tmpId = $id;
        }
    }

    public function create() {
        $rules = ([
            'nama' => ['required', 'min:3', 'max:255', 'unique:barangs'],
            'kode' => ['string', 'min:2', 'max:25'],
        ]);
        $validatedData = $this->validate($rules, $this->messages);
        $validatedData['userid'] = auth()->user()->id;

        Barang::create($validatedData);
        $msg = 'Tambah data ' . $this->nama . ' berhasil.';
        $this->clear();
        session()->flash('ok', $msg);
    }

    public function edit($id) {
        $this->getDataBarang($id);
    }

    public function update() {
        if ($this->tmpId) {
            $data = Barang::find($this->tmpId);

            $rules = ([
                'kode' => ['string', 'min:2', 'max:25'],
            ]);

            if ($this->nama != $data->nama) {
                $rules['nama'] = 'required|min:3|max:255|unique:barangs';
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

    public function confirmDelete($id) {
        $this->getDataBarang($id);
    }

    public function delete() {
        if ($this->tmpId) {
            $data = Barang::find($this->tmpId);
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
        $data = Barang::where('nama', 'like', '%' . $this->cari . '%')
            ->orderby($this->sortColumn, $this->sortDirection)
            ->paginate(12);

        return view('livewire.panel.barang.index', [
            'datas' => $data,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.panel',
            'title' => $this->title,
        ]);
    }
}
