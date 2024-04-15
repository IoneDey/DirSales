<?php

namespace App\Livewire\Panel\Tim;

use App\Models\Barangs as ModelsBarang;
use App\Models\Kotas as ModelsKota;
use App\Models\Pts as ModelsPt;
use App\Models\Timdt as ModelsTimdt;
use App\Models\Timhd as ModelsTimHd;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    use WithPagination;
    public function paginationView()
    {
        return 'vendor.livewire.bootstrap';
    }
    /*
    protected $paginationTheme = 'bootstrap';
    */

    public $title = 'Panel - Tim';

    public $temp_id;
    public $temp_idBarang;
    public $combination_unique;

    //data list
    public $dbPT;
    public $dbKota;
    public $dbBarang;

    //entity
    public $id;
    public $nomer;
    public $ptid;
    public $kotaid;
    public $tglawal;
    public $tglakhir;
    public string $pic;
    //data array barang
    public $nomerid;
    public $barangid;
    public $barang;
    public $hpp;
    public $hargajual;
    public $dataBarangDetail = [];

    //untuk status create datau edit
    public $isUpdate = false;
    public $isUpdateBarang = false;

    //untuk sort
    public $sortColumn = "nomer";
    public $sortDirection = "desc";



    public function clear()
    {
        $this->dataBarangDetail = [];

        $this->id = "";
        $this->nomer = "";
        $this->ptid = "";
        $this->kotaid = "";
        $this->tglawal = "";
        $this->tglakhir = "";
        $this->pic = "";
        $this->isUpdate = false;

        $this->clearBarang();
    }

    //header
    public function getData($id)
    {
        $data = ModelsTimHd::find($id);

        $this->id = $data->id;
        $this->nomer = $data->nomer;
        $this->ptid = $data->ptid;
        $this->kotaid = $data->kotaid;
        $this->tglawal = $data->tglawal;
        $this->tglakhir = $data->tglakhir;
        $this->pic = $data->pic;
        $this->isUpdate = true;

        $this->refreshBarangDet($data);
    }

    public function refreshBarangDet($dataTim)
    {
        $data = $dataTim;
        $this->dataBarangDetail = [];
        foreach ($data->joinTimdt as $timdt) {
            $item = [
                'barangid' => $timdt->barangid,
                'barang' => $timdt->joinBarang->nama . '-' . $timdt->joinBarang->kode,
                'hpp' => $timdt->hpp,
                'hargajual' => $timdt->hargajual
            ];
            $this->dataBarangDetail[] = $item;
        }
    }

    public function store()
    {
        $rules = ([
            'nomer' => [
                'required', 'min:4', 'max:6',
                Rule::unique('timhd')->where(function ($query) {
                    return $query->where('nomer', $this->nomer)
                        ->where('kotaid', $this->kotaid);
                })
            ],
            'ptid' => ['required'],
            'kotaid' => ['required'],
            'tglawal' => ['required', 'date'],
            'tglakhir' => ['required', 'date', 'after:tglawal'],
            'pic' => ['required', 'max:255'],

        ]);
        $pesan = [
            'nomer.required' => 'Nomer Tim wajib diisi.',
            'nomer.min' => 'Nomer Tim minimum 4 karakter.',
            'nomer.max' => 'Nomer Tim maximum 6 karakter.',
            'nomer.unique' => 'Kota sudah pernah ada dalam tim.',
            'ptid.required' => 'PT wajib diisi.',
            'kotaid.required' => 'Kota wajib diisi.',
            'tglawal.required' => 'Tgl Awal wajib diisi.',
            'tglakhir.required' => 'Tgl Akhir wajib diisi.',
            'tglakhir.after' => 'Tgl Akhir harus lebih besar tglawal.',
            'pic.required' => 'P.I.C wajib diisi.',
            'pic.max' => 'P.I.C maximum 255 karakter.',
        ];
        try {
            $validatedData = $this->validate($rules, $pesan);
            $validatedData['userid'] = auth()->user()->id;
            ModelsTimHd::create($validatedData);
            $msg = 'Data ' . $this->nomer . ' berhasil disimpan.';
            session()->flash('ok', $msg);
        } catch (ValidationException $e) {
            $errors = implode("\n", $e->validator->errors()->all());
            Session::flash('error', $errors);
        }
    }

    public function edit($id)
    {
        $this->getData($id);
        $this->temp_id = $id;
    }

    public function update()
    {
        $data = ModelsTimHd::find($this->temp_id);

        $rules = ([
            'ptid' => ['required'],
            'tglawal' => ['required', 'date'],
            'tglakhir' => ['required', 'date', 'after:tglawal'],
            'pic' => ['required', 'max:255'],

        ]);
        $pesan = [
            'tglawal.required' => 'Tgl Awal wajib diisi.',
            'tglakhir.required' => 'Tgl Akhir wajib diisi.',
            'tglakhir.after' => 'Tgl Akhir harus lebih besar tglawal.',
            'pic.required' => 'P.I.C wajib diisi.',
            'pic.max' => 'P.I.C maximum 255 karakter.',
        ];

        if ($data->nomer != $this->nomer || $data->ptid != $this->ptid || $data->kotaid != $this->kotaid) {
            $rules = [
                'nomer' => [
                    'required', 'min:4', 'max:6',
                    Rule::unique('timhd')->where(function ($query) {
                        return $query->where('nomer', $this->nomer)
                            ->where('kotaid', $this->kotaid);
                    })
                ],
                'ptid' => ['required'],
                'kotaid' => ['required'],
            ];
            $pesan = [
                'nomer.required' => 'Nomer Tim wajib diisi.',
                'nomer.min' => 'Nomer Tim minimum 4 karakter.',
                'nomer.max' => 'Nomer Tim maximum 6 karakter.',
                'nomer.unique' => 'Kota sudah pernah ada dalam tim.',
                'kotaid.required' => 'Kota wajib diisi.',
                'ptid.required' => 'PT wajib diisi.',
                'kotaid.required' => 'Kota wajib diisi.',
            ];
        }

        try {
            $validatedData = $this->validate($rules, $pesan);
            $validatedData['userid'] = auth()->user()->id;

            $data->update($validatedData);

            $msg = 'Data ' . $this->nomer . ' berhasil di-update.';
            session()->flash('ok', $msg);
        } catch (ValidationException $e) {
            $errors = implode("\n", $e->validator->errors()->all());
            Session::flash('error', $errors);
        }
    }

    public function delete_confirm($id)
    {
        $this->temp_id = $id;
        $this->getData($id);
    }

    public function delete()
    {
        try {
            $id = $this->temp_id;
            $data = ModelsTimHd::find($id);
            $data->delete();
            $msg = 'Data ' . $this->nomer . ' berhasil di-delete.';
            session()->flash('ok', $msg);
            $this->clear();
        } catch (\Exception $e) {
            $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.'));
            session()->flash('error', $errors);
        }
    }

    //detail
    public function clearBarang()
    {
        $this->nomerid = "";
        $this->barangid = "";
        $this->barang = "";
        $this->hpp = "";
        $this->hargajual = "";

        $this->isUpdateBarang = false;
    }

    public function getDataBarang($idBrg)
    {
        $data = ModelsTimdt::where([
            'nomerid' => $this->id,
            'barangid' => $idBrg
        ])->first();

        $this->nomerid = $data->nomerid;
        $this->barangid = $data->barangid;
        $this->barang = $data->joinBarang->nama . ' ' . $data->joinBarang->kode;
        $this->hpp = $data->hpp;
        $this->hargajual = $data->hargajual;
        $this->isUpdateBarang = true;
    }

    public function storeBarang()
    {
        $tmp = ModelsBarang::find($this->barangid);
        $this->nomerid = $this->nomer;


        $rules = [
            'nomerid' => 'required',
            'barangid' => [
                'required',
                Rule::unique('timdt')->where(function ($query) {
                    return $query->where('nomerid', $this->id)
                        ->where('barangid', $this->barangid);
                })
            ],
            'hpp' => 'required|numeric|min:0',
            'hargajual' => 'required|numeric|min:0'
        ];
        $pesan = [
            'nomerid.required' => 'Tim wajib diisi.',
            'barangid.required' => 'Barang belum dipilih.',
            'barangid.unique' => 'Barang sudah ada.',
            'hpp.required' => 'Hpp wajib diisi.',
            'hargajual.required' => 'Harga jual wajib diisi.',
        ];
        try {
            $validatedData = $this->validate($rules, $pesan);
            $validatedData['nomerid'] = $this->id;
            $validatedData['userid'] = auth()->user()->id;
            ModelsTimdt::create($validatedData);

            $item = [
                'barangid' => $this->barangid,
                'barang' => $tmp->nama . '-' . $tmp->kode,
                'hpp' => $this->hpp,
                'hargajual' => $this->hargajual
            ];
            $this->dataBarangDetail[] = $item;

            $msg = 'Data ' . $tmp->nama . ' berhasil ditambahkan.';
            session()->flash('ok', $msg);
            $this->clearBarang();
        } catch (ValidationException $e) {
            $errors = implode("\n", $e->validator->errors()->all());
            Session::flash('error', $errors);
        }
    }

    public function editBarang($id)
    {
        $this->temp_idBarang = $id;
        $this->getDataBarang($this->temp_idBarang);
    }

    public function updateBarang()
    {
    }

    public function deleteBarang_confirm($id)
    {
        $this->temp_idBarang = $id;
        $this->getDataBarang($this->temp_idBarang);
        //$this->getData($id);
    }

    public function deleteBarang()
    {
        try {
            $idBrg = $this->temp_idBarang;
            $data = ModelsTimdt::where([
                'nomerid' => $this->id,
                'barangid' => $idBrg
            ])->first();

            //del data
            $data->delete();

            //del array
            $collection = collect($this->dataBarangDetail);
            $filteredCollection = $collection->reject(function ($item) use ($idBrg) {
                return $item['barangid'] === $idBrg;
            });
            $this->dataBarangDetail = $filteredCollection->values()->all();

            $msg = 'Data ' . $this->nomer . ' barang ' . $this->barang . 'berhasil di-delete.';
            session()->flash('ok', $msg);
            $this->clearBarang();
        } catch (\Exception $e) {
            //$errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.'));
            $errors = $e->getMessage();
            session()->flash('error', $errors);
        }
    }

    public function mount()
    {
        $this->dbPT = ModelsPt::all();
        $this->dbKota = ModelsKota::all();
        $this->dbBarang = ModelsBarang::all();
    }

    /*
    public function updatedptid($dbPT)
    {
        $this->ptid = $dbPT;
        //dd($this->ptid);
    }

    public function updatedkotaid($dbKota)
    {
        $this->kotaid = $dbKota;
    }
    */
    public function sort($column)
    {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function render()
    {
        //$dbTimHd = ModelsTimHd::orderBy($this->sortColumn, $this->sortDirection)->paginate(5);

        $dbTimHd = ModelsTimHd::join('kotas', 'timhd.kotaid', '=', 'kotas.id')
            ->orderBy($this->sortColumn, $this->sortDirection)
            ->select('timhd.*')
            ->paginate(2);

        return view('livewire.panel.tim.index', [
            'dbTimHds' => $dbTimHd,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.panel',
            'title' => $this->title,
        ]);
    }
}
