<?php

namespace App\Livewire\Panel\User;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component {
    use WithPagination;
    use WithFileUploads;

    public $title = 'User';

    public $name;
    public $username;
    public $email;
    public $password;
    public $roles = 'SUPERVISOR';
    public $image;

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
        $this->name = "";
        $this->username = "";
        $this->email = "";
        $this->password = "";
        $this->roles = "SUPERVISOR";
        $this->image = null;
        $this->isUpdate = false;
        $this->tmpId = null;
    }

    public function getDataUser($id) {
        if ($id != "") {
            $data = User::find($id);

            $this->name = $data->name;
            $this->username = $data->username;
            $this->email = $data->email;
            //$this->password = $data->password;
            $this->roles = $data->roles;
            $this->image = $data->image;
            $this->isUpdate = true;
            $this->tmpId = $id;
        }
    }

    protected $messages = [
        'name.required' => 'nama wajib diisi.',
        'name.min' => 'nama minimal harus 3 karakter.',
        'name.max' => 'nama tidak boleh lebih dari 50 karakter.',
        'username.required' => 'user name wajib diisi.',
        'username.min' => 'user name minimal harus 3 karakter.',
        'username.max' => 'user name tidak boleh lebih dari 15 karakter.',
        'username.unique' => 'user name sudah dipakai.',
        'email.required' => 'email wajib diisi.',
        'email.email' => 'penulisan email tidak benar.',
        'email.unique' => 'email sudah dipakai.',
        'password.required' => 'password wajib diisi.',
        'password.min' => 'password minimal harus 8 karakter.',
        'roles.required' => 'roles wajib diisi.',
        'image.image' => 'file harus berupa gambar.',
        'image.max' => 'gambar tidak boleh lebih besar dari 1024 kilobyte.',
    ];

    public function create() {
        $rules = ([
            'name' => ['required', 'min:3', 'max:50'],
            'username' => ['required', 'min:3', 'max:15', 'unique:users'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
            'roles' => ['required'],
            'image' => ['nullable', 'sometimes', 'image', 'max:1024']
        ]);

        $validatedData = $this->validate($rules, $this->messages);
        $validatedData['password'] = Hash::make($this->password);
        if ($this->image) {
            $validatedData['image'] = $this->image->store('uploads', 'public');
        }

        User::create($validatedData);
        $msg = 'Tambah data ' . $this->name . ' berhasil.';
        $this->clear();
        session()->flash('ok', $msg);
    }

    public function edit($id) {
        $this->getDataUser($id);
    }

    public function update() {
        $data = User::find($this->tmpId);
        $rules = ([
            'name' => ['required', 'min:3', 'max:50'],
            'roles' => ['required'],
            'image' => ['nullable', 'sometimes', 'image', 'max:1024']
        ]);

        if ($data->username != $this->username) {
            $rules['username'] = ['required', 'min:3', 'max:15', 'unique:users'];
        }

        if ($data->email != $this->email) {
            $rules['email'] = ['required', 'email', 'unique:users'];
        }

        $validatedData = $this->validate($rules, $this->messages);
        if ($this->image) {
            $validatedData['image'] = $this->image->store('uploads', 'public');
        }

        $data->update($validatedData);
        $msg = 'Update data ' . $this->name . ' berhasil.';
        $this->clear();
        session()->flash('ok', $msg);
    }

    public function confirmDelete($id) {
        $this->getDataUser($id);
    }

    public function delete() {
        try {
            $msg = 'Data ' . $this->name . ' berhasil dihapus.';
            User::find($this->tmpId)->delete();
            $this->clear();
            session()->flash('ok', $msg);
        } catch (\Exception $e) {
            $errors = implode("\n", array('Terjadi kesalahan:   ', 'Data sudah terpakai.', 'Error code: ' . $e->getCode()));
            session()->flash('error', $errors);
        }
    }

    public function render() {
        $data = User::where('name', 'like', '%' . $this->cari . '%')
            ->orWhere('username', 'like', '%' . $this->cari . '%')
            ->orWhere('email', 'like', '%' . $this->cari . '%')
            ->paginate(5);

        return view('livewire.panel.user.index', [
            'datas' => $data,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.panel',
            'title' => $this->title,
        ]);
    }
}
