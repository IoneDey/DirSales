<?php

namespace App\Livewire\Panel\Kota;

use App\Models\Kota;
use App\Models\Provinsi;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    public $title = 'Master Kota';

    //--field
    #[Rule('required|min:3|max:255')]
    public $nama;
    #[Rule('required')]
    public $provinsiid;

    protected $messages = [
        'nama.required' => 'nama wajib diisi.',
        'nama.min' => 'nama minimal harus 3 karakter.',
        'nama.max' => 'nama tidak boleh lebih dari 255 karakter.',
        'nama.unique' => 'nama sudah dipakai.',
        'provinsiid.required' => 'provinsi wajib diisi.',
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

    public $dbProvinsis;

    public function mount()
    {
        $this->dbProvinsis = Provinsi::all();
    }

    public function clear()
    {
        $this->nama = "";
        $this->provinsiid = "";
        $this->isUpdate = false;
        $this->tmpId = null;
    }

    public function getDataKota($id)
    {
        if ($id != "") {
            $data = Kota::find($id);

            $this->nama = $data->nama;
            $this->provinsiid = $data->provinsiid;

            $this->isUpdate = true;
            $this->tmpId = $id;
        }
    }

    public function create()
    {

        $rules = ([
            'nama' => [
                'required', 'min:3', 'max:255',
                Rule::unique('kotas')->where(function ($query) {
                    return $query->where('nama', $this->nama)
                        ->where('provinsiid', $this->provinsiid);
                })
            ],
            'provinsiid' => ['required']
        ]);

        $validatedData = $this->validate($rules, $this->messages);
        $validatedData['userid'] = auth()->user()->id;

        Kota::create($validatedData);
        $msg = 'Tambah data ' . $this->nama . ' berhasil.';
        $this->clear();
        session()->flash('ok', $msg);
    }

    public function edit($id)
    {
        $this->getDataKota($id);
    }

    public function update()
    {
        if ($this->tmpId) {
            $data = Kota::find($this->tmpId);

            $rules = [
                'provinsiid' => 'required',
            ];

            if ($this->nama != $data->nama) {
                $rules['nama'] = [
                    'required', 'min:3', 'max:255',
                    Rule::unique('kotas')->where(function ($query) {
                        return $query->where('nama', $this->nama)
                            ->where('provinsiid', $this->provinsiid);
                    })
                ];
            }

            try {
                $validatedData = $this->validate($rules, $this->messages);
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

    public function confirmDelete($id)
    {
        $this->getDataKota($id);
    }

    public function delete()
    {
        if ($this->tmpId) {
            $data = Kota::find($this->tmpId);
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
        $data = Kota::where(function ($query) {
            $query->whereHas('joinProvinsi', function ($subquery) {
                $subquery->where('nama', 'like', '%' . $this->cari . '%');
            })
                ->orWhere('nama', 'like', '%' . $this->cari . '%');
        })
            ->orderby($this->sortColumn, $this->sortDirection)
            ->paginate(12);

        return view('livewire.panel.kota.index', [
            'datas' => $data,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.panel',
            'title' => $this->title,
        ]);
    }
}
