<?php

namespace App\Livewire\Main\Penjualan;

use App\Models\Penjualandt;
use App\Models\Penjualanhd;
use App\Models\Timsetup;
use App\Models\Timsetuppaket;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Validasiedit extends Component {
    use WithFileUploads;

    public $title;
    public $statusUser;

    public $id;
    //entity
    public $timsetupid;
    public $nota;
    public $angsuranhari;
    public $angsuranperiode;
    public $kecamatan;
    public $tgljual;
    public $customernama;
    public $customeralamat;
    public $customernotelp;
    public $shareloc;
    public $namasales;
    public $namalock;
    public $namadriver;
    public $pjkolektornota;
    public $pjadminnota;
    public $fotoktp;
    public $fotonota;
    public $fotonotarekap;

    public $penjualanhdid;
    public $timsetupnama;
    public $jumlahTotal;

    //barang-paket
    public $timsetuppaketid;
    public $jumlah;
    public $jumlahkoreksi;

    public $penjualandtid;

    public $paketnama;

    //lain2x
    public $isUpdate;
    public $isUpdatePaket;

    //db
    public $dbTimsetups;

    public $roles;

    public function resetErrors() {
        $this->resetErrorBag();
    }

    public function mount($id) {
        $this->id = $id;
        $this->statusUser = auth()->user()->roles;
        $this->title = 'Edit Penjualan (' . $this->statusUser . ')';

        $this->dbTimsetups = Timsetup::get();

        $this->getData($id);
    }

    //head
    public function update() {
        //$this->js('alert("Under construction!!!")');
        if ($this->penjualanhdid) {
            $data = Penjualanhd::find($this->penjualanhdid);

            $rules = [
                'timsetupid' => 'required',
                'kecamatan' => 'string|max:150',
                'tgljual' => 'required|date',
                'angsuranhari' => 'required|numeric|min:1|max:10',
                'angsuranperiode' => 'required|numeric|min:1|max:10',
                'customernama' => 'required|string|max:150',
                'customeralamat' => 'required|string|max:255',
                'customernotelp' => 'required|string',
                'shareloc' => 'required|string|max:150',
                'namasales' => 'required|string|max:150',
                'namalock' => 'required|string|max:150',
                'namadriver' => 'required|string|max:150',
                'pjkolektornota' => 'required|string|max:150',
                'pjadminnota' => 'required|string|max:150',
            ];

            if ($this->nota != $data->nota) {
                $rules['nota'] = ['required', 'string', 'unique:penjualanhds'];
            }
            if ($this->fotoktp != $data->fotoktp) {
                $rules['fotoktp'] = ['required', 'sometimes', 'image', 'max:1024'];
            }
            if ($this->fotonota != $data->fotonota) {
                $rules['fotonota'] = ['required', 'sometimes', 'image', 'max:1024'];
            }
            if ($this->fotonotarekap != $data->fotonotarekap) {
                $rules['fotonotarekap'] = ['required', 'sometimes', 'image', 'max:1024'];
            }

            $validated = $this->validate($rules);
            if (!is_string($this->fotoktp)) {
                if ($this->fotoktp) {
                    $validated['fotoktp'] = $this->fotoktp->storeAs('uploads', 'ktp-' . $this->nota . '.jpg', 'public');
                }
            }

            if (!is_string($this->fotonota)) {
                if ($this->fotonota) {
                    $validated['fotonota'] = $this->fotonota->storeAs('uploads', 'nota-' . $this->nota . '.jpg', 'public');
                }
            }

            if (!is_string($this->fotonotarekap)) {
                if ($this->fotonotarekap) {
                    $validated['fotonotarekap'] = $this->fotonotarekap->storeAs('uploads', 'notarekap-' . $this->nota . '.jpg', 'public');
                }
            }

            $validated['penjualanhdid'] = $this->penjualanhdid;
            //$validated['userid'] = auth()->user()->id;

            try {
                $data->update($validated);

                $msg = 'Update data Nota: ' . $this->nota . ' berhasil.';
                $this->clearPaket();
                session()->flash('ok', $msg);
            } catch (\Exception $e) {
                $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.', '(' . $e->getMessage() . ')'));
                session()->flash('error', $errors);
            }
        }
    }

    public function getData($id) {
        if (!auth()->user()->id) {
            return redirect('/');
        }

        $this->roles = auth()->user()->roles;
        if (($this->roles != 'SUPERVISOR') && ($this->roles != 'LOCK')) {
            return redirect('/');
        }

        $data = Penjualanhd::find($id);
        if (auth()->user()->roles == 'LOCK') {
            if ($data->status != "Entry Valid") {
                return redirect('/');
            }
        };

        $this->penjualanhdid = $data->id;
        $this->timsetupid = $data->timsetupid;
        $this->nota = $data->nota;
        $this->angsuranhari = $data->angsuranhari;
        $this->angsuranperiode = $data->angsuranperiode;
        $this->kecamatan = $data->kecamatan;
        $this->tgljual = $data->tgljual;
        $this->customernama = $data->customernama;
        $this->customeralamat = $data->customeralamat;
        $this->customernotelp = $data->customernotelp;
        $this->shareloc = $data->shareloc;
        $this->namasales = $data->namasales;
        $this->namalock = $data->namalock;
        $this->namadriver = $data->namadriver;
        $this->pjkolektornota = $data->pjkolektornota;
        $this->pjadminnota = $data->pjadminnota;
        $this->fotoktp = $data->fotoktp;
        $this->fotonota = $data->fotonota;
        $this->fotonotarekap = $data->fotonotarekap;

        $this->timsetupnama = $data->joinTimSetup->joinTim->nama;

        $this->isUpdate = true;
        //dump($this->timsetupnama);
    }
    //end head

    //paket
    public function updatedtimsetuppaketid($id) {
        $this->paketnama = Timsetuppaket::where('id', $id)->plucK('nama')->first();
    }

    public function createPaket() {
        $rulepakets = [
            'timsetuppaketid' => [
                'required',
                Rule::unique('penjualandts')->where(function ($query) {
                    return $query->where('timsetuppaketid', $this->timsetuppaketid)
                        ->where('penjualanhdid', $this->penjualanhdid);
                })
            ],
            'jumlahkoreksi' => ['required', 'numeric', 'min:1'],
        ];
        $pesan = [
            'timsetuppaketid.required' => 'Paket wajib diisi.',
            'timsetuppaketid.unique' => 'Paket sudah ada.',
        ];
        $validatedPaket = $this->validate($rulepakets, $pesan);
        $validatedPaket['penjualanhdid'] = $this->penjualanhdid;
        $validatedPaket['jumlah'] = 0;
        $validatedPaket['userkoreksiid'] = auth()->user()->id;
        $validatedPaket['validatedkoreksi_at'] = now();

        Penjualandt::create($validatedPaket);
        $msg = 'Tambah data ' . $this->paketnama . ' berhasil.';
        session()->flash('ok', $msg);

        $this->clearPaket();
    }

    public function editPaket($id) {
        $data = Penjualandt::find($id);
        $this->timsetuppaketid = $data->timsetuppaketid;
        $this->jumlah = $data->jumlah;
        $this->jumlahkoreksi = $data->jumlah + $data->jumlahkoreksi;

        $this->penjualandtid = $data->id;
        $this->paketnama = $data->joinTimSetupPaket->nama;
        $this->isUpdatePaket = true;
    }

    public function updatePaket() {
        if ($this->penjualandtid) {
            $data = Penjualandt::find($this->penjualandtid);

            // $rulesPaket = [
            //     'jumlahkoreksi' => [
            //         'required', 'numeric', function ($attribute, $value, $fail) use ($data) {
            //             $jumlah = $data->jumlah;
            //             if ($value < (-1 * $jumlah)) {
            //                 $fail('Nilai ' . $attribute . ' tidak boleh lebih kecil dari -' . $jumlah);
            //             }
            //         }
            //     ]
            // ];
            $rulesPaket = ['jumlahkoreksi' => ['required', 'numeric', 'min:1']];
            if ($this->timsetuppaketid != $data->timsetuppaketid) {
                $rulesPaket['timsetuppaketid'] = [
                    'required',
                    Rule::unique('penjualandts')->where(function ($query) {
                        return $query->where('timsetuppaketid', $this->timsetuppaketid)
                            ->where('penjualanhdid', $this->penjualanhdid);
                    })
                ];
            }
            $validatePaket = $this->validate($rulesPaket);
            $validatePaket['penjualanhdid'] = $this->penjualanhdid;
            $validatePaket['userkoreksiid'] = auth()->user()->id;
            $validatePaket['jumlahkoreksi'] = $this->jumlahkoreksi - $this->jumlah;
            $validatePaket['validatedkoreksi_at'] = now();

            try {
                $data->update($validatePaket);

                $msg = 'Update data ' . $this->paketnama . ' berhasil.';
                $this->clearPaket();
                session()->flash('ok', $msg);
            } catch (\Exception $e) {
                $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.', '(' . $e->getMessage() . ')'));
                session()->flash('error', $errors);
            }
        }
    }

    public function confirmDeletePaket($id) {
        $data = Penjualandt::find($id);
        $this->timsetuppaketid = $data->timsetuppaketid;
        $this->jumlah = $data->jumlah;

        $this->penjualandtid = $data->id;
        $this->paketnama = $data->joinTimSetupPaket->nama;
        $this->isUpdatePaket = true;
    }

    public function deletePaket() {
        if ($this->penjualandtid) {
            $data = Penjualandt::find($this->penjualandtid);
            $msg = 'Data ' . $this->paketnama . ' berhasil dihapus.';
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
        $this->timsetuppaketid = "";
        $this->jumlah = "";
        $this->jumlahkoreksi = "";
        $this->paketnama = "";

        $this->isUpdatePaket = false;
    }
    //end paket

    public function render() {
        $dbTimssetuppakets = Timsetuppaket::where('timsetupid', $this->timsetupid)->get();
        $dbPenjualandts = Penjualandt::where('penjualanhdid', $this->penjualanhdid)->get();

        return view('livewire.main.penjualan.validasiedit', [
            'dbTimssetuppakets' => $dbTimssetuppakets,
            'dbPenjualandts' => $dbPenjualandts,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
