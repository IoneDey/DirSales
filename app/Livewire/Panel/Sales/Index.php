<?php

namespace App\Livewire\Panel\Sales;

use App\Models\Pt;
use App\Models\Sales;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component {
    use WithPagination;
    public $title = 'Master Sales';

    //--field
    #[Rule('required|min:3|max:255')]
    public $nama;
    #[Rule('string|min:10|max:20')]
    public $notelp = '';
    #[Rule('required')]
    public $ptid;

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

    public $dbPts;

    public function mount() {
        // $this->dbPts = Pt::all();
        $this->dbPts = Pt::select('id', 'nama')->get();
    }

    public function clear() {
        $this->nama = "";
        $this->notelp = "";
        $this->ptid = "";
        $this->isUpdate = false;
        $this->tmpId = null;
    }

    public function getDataTim($id) {
        if ($id != "") {
            $data = Sales::find($id);

            $this->nama = $data->nama;
            $this->notelp = $data->notelp;
            $this->ptid = $data->ptid;

            $this->isUpdate = true;
            $this->tmpId = $id;
        }
    }

    protected $messages = [
        'nama.required' => 'nama wajib diisi.',
        'nama.min' => 'nama minimal harus 3 karakter.',
        'nama.max' => 'nama tidak boleh lebih dari 255 karakter.',
        'nama.unique' => 'nama sudah ada di PT yang dipilih.',
        'notelp.min' => 'nomor telp minimal harus 10 digit.',
        'notelp.max' => 'nomer telp tidak boleh lebih dari 20 digit.',
        'ptid.required' => 'pt wajib diisi.',
    ];

    public function create() {

        $rules = ([
            'nama' => [
                'required', 'min:3', 'max:255',
                Rule::unique('tims')->where(function ($query) {
                    return $query->where('nama', $this->nama)
                        ->where('ptid', $this->ptid);
                })
            ],
            'notelp' => ['string', 'min:10', 'max:20'],
            'ptid' => ['required']
        ]);

        $validatedData = $this->validate($rules, $this->messages);
        $validatedData['userid'] = auth()->user()->id;

        Sales::create($validatedData);
        $msg = 'Tambah data ' . $this->nama . ' berhasil.';
        $this->clear();
        session()->flash('ok', $msg);
    }

    public function edit($id) {
        $this->getDataTim($id);
    }

    public function update() {
        if ($this->tmpId) {
            $data = Sales::find($this->tmpId);

            $rules = [
                'notelp' => 'string|min:10|max:20',
                'ptid' => 'required',
            ];

            if (($this->nama != $data->nama) || ($this->ptid != $data->ptid)) {
                $rules['nama'] = [
                    'required', 'min:3', 'max:255',
                    Rule::unique('sales')->where(function ($query) {
                        return $query->where('nama', $this->nama)
                            ->where('ptid', $this->ptid);
                    })
                ];
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
        $this->getDataTim($id);
    }

    public function delete() {
        if ($this->tmpId) {
            $data = Sales::find($this->tmpId);
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
        $data = Sales::where(function ($query) {
            $query
                ->whereHas('joinPt', function ($subquery) {
                    $subquery->where('nama', 'like', '%' . $this->cari . '%');
                })
                ->orWhere('nama', 'like', '%' . $this->cari . '%');
        })
            ->orderby($this->sortColumn, $this->sortDirection)
            ->paginate(12);

        return view('livewire.panel.sales.index', [
            'datas' => $data,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.panel',
            'title' => $this->title,
        ]);
    }
}
