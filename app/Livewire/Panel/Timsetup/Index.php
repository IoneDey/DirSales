<?php

namespace App\Livewire\Panel\Timsetup;


use App\Models\Barang;
use App\Models\Kota2022;
use App\Models\Kota;
use App\Models\Tim;
use App\Models\Timsetup;
use App\Models\Timsetupbarang;
use App\Models\Timsetuppaket;
use App\customClass\myNumber;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component {
    use WithPagination;

    public $title = 'Setup Tim';

    //timsetups
    public $timid;
    public $kotaid;
    public $tglawal;
    public $tglakhir;
    public $angsuranhari;
    public $angsuranperiode;
    public $pic;

    public $timIdAktif;
    public $timNamaAktif;
    public $timKotaAktif;

    public $isUpdateTim = false;

    //timsetuppakets
    public $timsetupid;
    public $nama;
    public $hargajual;

    public $timIdAktifPaket;
    public $isUpdatePaket = false;

    //timsetupbarangs
    public $barangid;
    public $hpp;

    public $timIdAktifBarang;
    public $timBarangAktif;
    public $isUpdateBarang = false;

    //database
    public $dbTims;
    public $dbKotas;
    public $dbKota2022s;
    public $dbBarangs;

    //navigator editor
    public $idswitchMenu = 1;
    public $idswitchItem = 1;

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
    public $sortColumn = "tglawal";
    public $sortDirection = "asc";
    //--end sort

    public function resetErrors() {
        $this->resetErrorBag();
    }

    public function mount() {
        // $this->timIdAktif = 1;
        // $this->timIdAktifPaket = 1;
        // $this->myswitch(1);
        $this->dbTims = Tim::orderby('nama', 'asc')->get();
        $this->dbKotas = Kota::orderby('nama', 'asc')->get();
        $this->dbBarangs = Barang::orderby('nama', 'asc')->get();
    }

    public function myswitch($no) {
        $this->idswitchMenu = $this->timIdAktif ? 2 : 1;
        $this->idswitchMenu = $this->timIdAktifPaket ? 3 : 2;
        //$idswitchMenu = $this->timid ? 1 : 0;
        $this->idswitchItem = $no;
    }

    //timsetup
    public function updatedtglawal() {
        $tglAwal = Carbon::parse($this->tglawal);
        $tglAkhir = $tglAwal->endOfMonth();
        $this->tglakhir = $tglAkhir->toDateString();
    }

    public function updatedtimid() {
        $data = Tim::find($this->timid);
        if ($data) {
            $this->timNamaAktif = $data->nama;
        } else {
            $this->timNamaAktif = "";
        }
    }

    public function updatedkotaid() {
        $data = Kota::find($this->kotaid);
        if ($data) {
            $this->timKotaAktif = $data->nama;
        } else {
            $this->timKotaAktif = "";
        }
    }

    public function createTimSetup() {
        $rules = ([
            'timid' => [
                'required',
                Rule::unique('timsetups')->where(function ($query) {
                    return $query->where('timid', $this->timid)
                        ->where('kotaid', $this->kotaid);
                })
            ],
            'kotaid' => ['required'],
            'tglawal' => ['required', 'date'],
            'tglakhir' => ['required', 'date', 'after:tglawal'],
            'angsuranhari' => ['required', 'numeric', 'min:1', 'max:10'],
            'angsuranperiode' => ['required', 'numeric', 'min:1', 'max:10'],
            'pic' => ['required', 'min:3', 'max:255'],
        ]);

        $pesan = ([
            'timid.required' => 'tim wajib diisi.',
            'timid.unique' => 'tim-kota sudah ada.',
            'kotaid.required' => 'kota wajib diisi.',
            'tglawal.required' => 'tgl awal wajib diisi.',
            'tglawal.date' => 'tgl awal format salah.',
            'tglakhir.required' => 'tgl akhir wajib diisi.',
            'tglakhir.date' => 'tgl akhir format salah.',
            'tglakhir.after' => 'tgl akhir harus lebih besar dari tgl awal.',
            'angsuranhari.required' => 'angsuran-hari wajib diisi.',
            'angsuranhari.numeric' => 'angsuran-hari harus angka.',
            'angsuranhari.min' => 'angsuran-hari minimal 1.',
            'angsuranhari.max' => 'angsuran-hari maximal 10.',
            'angsuranperiode.required' => 'angsuran-periode wajib diisi.',
            'angsuranperiode.numeric' => 'angsuran-periode harus angka.',
            'angsuranperiode.min' => 'angsuran-periode minimal 1.',
            'angsuranperiode.max' => 'angsuran-periode maximal 10.',
            'pic.required' => 'pic wajib diisi.',
            'pic.min' => 'pic minimal harus 3 karakter.',
            'pic.max' => 'pic tidak boleh lebih dari 255 karakter.',
        ]);
        $validate = $this->validate($rules, $pesan);
        $validate['userid'] = auth()->user()->id;

        Timsetup::create($validate);

        $dbTim = $this->dbTims->where('id', $this->timid)->first();
        $dbKota = $this->dbKotas->where('id', $this->kotaid)->first();

        $msg = 'Tambah data ' . $dbTim->nama . ' - ' . $dbKota->nama . ' berhasil.';
        $this->myswitch(2);
        session()->flash('ok', $msg);
        $this->timIdAktif = Timsetup::where('timid', $this->timid)
            ->where('kotaid', $this->kotaid)
            ->first()->id;
        $this->isUpdateTim = true;
    }

    public function editTimSetup($id) {
        $this->getdataTimSetup($id);
        $this->myswitch(1);
    }

    public function updateTimSetup() {
        if ($this->timIdAktif) {
            $data = Timsetup::find($this->timIdAktif);

            $rules = [
                'kotaid' => ['required'],
                'tglawal' => ['required', 'date'],
                'tglakhir' => ['required', 'date', 'after:tglawal'],
                'angsuranhari' => ['required', 'numeric', 'min:1', 'max:10'],
                'angsuranperiode' => ['required', 'numeric', 'min:1', 'max:10'],
                'pic' => ['required', 'min:3', 'max:255'],
            ];

            if (($this->timid != $data->timid) || ($this->kotaid != $data->kotaid)) {
                $rules['timid'] = [
                    'required',
                    Rule::unique('timsetups')->where(function ($query) {
                        return $query->where('timid', $this->timid)
                            ->where('kotaid', $this->kotaid);
                    })
                ];
            }

            try {
                $validate = $this->validate($rules);
                $validate['userid'] = auth()->user()->id;
                $data->update($validate);

                $dbTim = $this->dbTims->where('id', $this->timid)->first();
                $dbKota = $this->dbKotas->where('id', $this->kotaid)->first();

                $msg = 'Update data ' . $dbTim->nama . ' - ' . $dbKota->nama . ' berhasil.';
                session()->flash('ok', $msg);
            } catch (\Exception $e) {
                $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.', '(' . $e->getMessage() . ')'));
                session()->flash('error', $errors);
            }
        }
    }

    public function confirmDeleteTim($id) {
        $this->getdataTimSetup($id);
    }

    public function deleteTim() {
        if ($this->timIdAktif) {
            $data = Timsetup::find($this->timIdAktif);
            $msg = 'Data ' . $this->timNamaAktif . ' berhasil dihapus.';
            try {
                $data->delete();
                $this->clearTimSetup();
                session()->flash('ok', $msg);
            } catch (\Exception $e) {
                $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.'));
                session()->flash('error', $errors);
            }
            //$this->js('alert("$this->msg")');
        }
    }

    public function clearTimSetup() {
        $this->timIdAktif = "";
        $this->timid = "";
        $this->kotaid = "";
        $this->tglawal = "";
        $this->tglakhir = "";
        $this->angsuranhari = "";
        $this->angsuranperiode = "";
        $this->pic = "";
        $this->idswitchMenu = 0;
        $this->idswitchItem = 1;
        $this->isUpdateTim = false;
        //$this->reset();
    }

    public function getdataTimSetup($id) {
        $data = Timsetup::find($id);
        $this->timIdAktif = $data->id;
        $this->timid = $data->timid;
        $this->kotaid = $data->kotaid;
        $this->tglawal = $data->tglawal;
        $this->tglakhir = $data->tglakhir;
        $this->angsuranhari = $data->angsuranhari;
        $this->angsuranperiode = $data->angsuranperiode;
        $this->pic = $data->pic;
        $this->timNamaAktif = $data->joinTim->nama;
        $this->timKotaAktif = $data->joinkota->nama;
        $this->isUpdateTim = true;

        $this->timIdAktifPaket = "";
        $this->clearPaket();
        $this->clearBarang();

        $this->myswitch(1);
        //$this->getdataTimSetupPaketAll($this->timIdAktif);
    }
    //end timsetup

    //timsetuppaket
    public function createTimSetupPaket() {
        $this->hargajual = myNumber::str2Float($this->hargajual);

        $rulesPaket = ([
            'nama' => [
                'required', 'min:3', 'max:50',
                Rule::unique('timsetuppakets')->where(function ($query) {
                    return $query->where('nama', $this->nama)
                        ->where('timsetupid', $this->timIdAktif);
                })
            ],
            'hargajual' => ['required', 'numeric', 'min:0'],
        ]);
        $validatePaket = $this->validate($rulesPaket);
        $validatePaket['timsetupid'] = $this->timIdAktif;
        $validatePaket['userid'] = auth()->user()->id;

        Timsetuppaket::create($validatePaket);

        $msg = 'Tambah data ' . $this->nama . ' berhasil.';
        session()->flash('ok', $msg);
        $this->timIdAktifPaket = Timsetuppaket::where('timsetupid', $this->timIdAktif)->where('nama', $this->nama)->first()->id;
        $this->myswitch(3);
        $this->isUpdatePaket = true;
    }

    public function editPaket($id, $edited) {
        $this->getdataTimSetupPaket($id, $edited);
    }

    public function updatePaket() {
        if ($this->timIdAktifPaket) {
            $data = Timsetuppaket::find($this->timIdAktifPaket);

            $this->hargajual = mynumber::str2Float($this->hargajual);
            $rulesPaket = [
                'hargajual' => ['required', 'numeric', 'min:0'],
            ];

            if ($this->nama != $data->nama) {
                $rulesPaket['nama'] = [
                    'required', 'min:3', 'max:50',
                    Rule::unique('timsetuppakets')->where(function ($query) {
                        return $query->where('nama', $this->nama)
                            ->where('timsetupid', $this->timIdAktif);
                    })
                ];
            }

            try {
                $validatePaket = $this->validate($rulesPaket);
                $validatePaket['timsetupid'] = $this->timIdAktif;
                $validatePaket['userid'] = auth()->user()->id;
                $data->update($validatePaket);

                $msg = 'Update data ' . $this->nama . ' berhasil.';
                //$this->clearBarang();
                $this->hargajual = mynumber::float2Str($this->hargajual);
                session()->flash('ok', $msg);
            } catch (\Exception $e) {
                $this->hargajual = myNumber::float2Str($this->hargajual);
                $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.', '(' . $e->getMessage() . ')'));
                session()->flash('error', $errors);
            }
        }
    }

    public function confirmDeletePaket($id, $edited) {
        $this->getdataTimSetupPaket($id, $edited);
    }

    public function deletePaket() {
        if ($this->timIdAktifPaket) {
            $data = Timsetuppaket::find($this->timIdAktifPaket);
            $msg = 'Data ' . $this->nama . ' berhasil dihapus.';
            try {
                $data->delete();
                $this->clearPaket();
                session()->flash('ok', $msg);
            } catch (\Exception $e) {
                $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.'));
                session()->flash('error', $errors);
            }
            //$this->js('alert("$this->msg")');
        }
    }

    public function clearPaket() {
        $this->timsetupid = "";
        $this->nama = "";
        $this->hargajual = "";
        $this->timIdAktifPaket = "";
        $this->isUpdatePaket = false;
        $this->myswitch(2);
    }

    public function getdataTimSetupPaket($id, $edited) {
        $data = Timsetuppaket::find($id);
        $this->timsetupid = $data->timsetupid;
        $this->nama = $data->nama;
        $this->hargajual = myNumber::float2Str($data->hargajual);
        $this->timIdAktifPaket = $data->id;
        $this->isUpdatePaket = true;
        $this->myswitch(2);
        if ($edited) {
            $this->myswitch(3);
        }

        $this->clearBarang();
    }
    //end timsetuppaket

    //timsetupbarang
    public function updatedbarangid() {
        $data = Barang::find($this->barangid);
        if ($data) {
            $this->timBarangAktif = $data->nama;
        } else {
            $this->timBarangAktif = "";
        }
    }

    public function createTimSetupBarang() {

        $this->hpp = mynumber::str2Float($this->hpp);
        $rulesBarang = ([
            'barangid' => [
                'required',
                Rule::unique('timsetupbarangs')->where(function ($query) {
                    return $query->where('timsetuppaketid', $this->timIdAktifPaket)
                        ->where('barangid', $this->barangid);
                })
            ],
            'hpp' => ['required', 'numeric', 'min:0'],
        ]);
        $validateBarang = $this->validate($rulesBarang);
        $validateBarang['timsetuppaketid'] = $this->timIdAktifPaket;
        $validateBarang['userid'] = auth()->user()->id;

        Timsetupbarang::create($validateBarang);
        $msg = 'Tambah data ' . $this->timBarangAktif . ' berhasil.';
        session()->flash('ok', $msg);
        $this->clearBarang();
    }

    public function editBarang($id) {
        $this->getdataTimSetupBarang($id);
    }

    public function updateBarang() {
        if ($this->timIdAktifBarang) {
            $data = Timsetupbarang::find($this->timIdAktifBarang);

            $this->hpp = myNumber::str2Float($this->hpp);
            $rulesBarang = [
                'hpp' => ['required', 'numeric', 'min:0'],
            ];

            if ($this->barangid != $data->barangid) {
                $rulesBarang['barangid'] = [
                    'required',
                    Rule::unique('timsetupbarangs')->where(function ($query) {
                        return $query->where('timsetuppaketid', $this->timIdAktifPaket)
                            ->where('barangid', $this->barangid);
                    })
                ];
            }

            try {
                $validateBarang = $this->validate($rulesBarang);
                $validateBarang['timsetuppaketid'] = $this->timIdAktifPaket;
                $validateBarang['userid'] = auth()->user()->id;
                $data->update($validateBarang);

                $msg = 'Update data ' . $this->timBarangAktif . ' berhasil.';
                $this->clearBarang();
                session()->flash('ok', $msg);
            } catch (\Exception $e) {
                $this->hpp = myNumber::float2Str($this->hpp);
                $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.', '(' . $e->getMessage() . ')'));
                session()->flash('error', $errors);
            }
        }
    }

    public function confirmDeleteBarang($id) {
        $this->getdataTimSetupBarang($id);
    }

    public function deleteBarang() {
        if ($this->timIdAktifBarang) {
            $data = Timsetupbarang::find($this->timIdAktifBarang);
            $msg = 'Data ' . $this->timBarangAktif . ' berhasil dihapus.';
            try {
                $data->delete();
                $this->clearBarang();
                session()->flash('ok', $msg);
            } catch (\Exception $e) {
                $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.'));
                session()->flash('error', $errors);
            }
            //$this->js('alert("$this->msg")');
        }
    }

    public function getdataTimSetupBarang($id) {
        $data = Timsetupbarang::find($id);
        $this->barangid = $data->barangid;
        $this->hpp =  mynumber::float2Str($data->hpp);

        $this->timIdAktifBarang = $data->id;
        $this->updatedbarangid();
        $this->isUpdateBarang = true;
    }

    public function clearBarang() {
        $this->barangid = "";
        $this->hpp = "";

        $this->timIdAktifBarang = "";
        $this->timBarangAktif = "";
        $this->isUpdateBarang = false;
    }
    //end timsetupbarang

    public function sort($column) {
        $this->sortColumn = $column;
        $this->sortDirection = $this->sortDirection == 'asc' ? 'desc' : 'asc';
    }

    public function render() {
        $dataTimSetup = Timsetup::where(function ($query) {
            $query
                ->whereHas('joinTim', function ($subquery) {
                    $subquery->where('nama', 'like', '%' . $this->cari . '%');
                })
                ->whereHas('joinKota', function ($subquery) {
                    $subquery->where('nama', 'like', '%' . $this->cari . '%');
                });
        })
            ->orderby($this->sortColumn, $this->sortDirection)
            ->paginate(12);

        $dataTimSetupPaket = Timsetuppaket::where('timsetupid', $this->timIdAktif)
            ->paginate(5);

        $dataTimSetupBarang = Timsetupbarang::where('timsetuppaketid', $this->timIdAktifPaket)
            ->paginate(5);

        return view(
            'livewire.panel.timsetup.index',
            [
                'dbdatas' => $dataTimSetup,
                'dbdatapakets' => $dataTimSetupPaket,
                'dbdatabarangs' => $dataTimSetupBarang,
            ]
        )
            ->layout('layouts.app-layout', [
                'menu' => 'navmenu.panel',
                'title' => $this->title,
            ]);
    }
}
