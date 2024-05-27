<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component {
    use WithFileUploads;

    public $title = "Profile";
    public $id;

    public $name;
    public $username;
    public $email;
    public $password;
    public $roles = 'SUPERVISOR';
    public $image;

    public $data;

    public function mount() {
        $this->id = auth()->user()->id;
        $this->getDataUser($this->id);
    }

    public function updatedimage() {
        $data = User::find($this->id);
        $rules = ([
            'image' => ['nullable', 'image', 'max:1024']
        ]);

        $validatedData = $this->validate($rules);
        if ($this->image) {
            $validatedData['image'] = $this->image->store('uploads', 'public');
        }

        $data->update($validatedData);
        $this->getDataUser($this->id);
    }

    public function update() {
        $data = User::find($this->id);
        $rules['password'] = ['required', 'min:8'];
        $validate = $this->validate($rules);
        $this->password = Hash::make($this->password);
        $data->update($validate);
        $this->js('alert("Password baru sudah tersimpan.")');
        $this->password = "";
    }

    public function getDataUser($id) {
        if ($id != "") {
            $this->data = User::find($id);

            $this->name = $this->data->name;
            $this->username = $this->data->username;
            $this->email = $this->data->email;
            //$this->password = $this->data->password;
            $this->roles = $this->data->roles;
            $this->image = $this->data->image;
        }
    }

    public function render() {
        return view('livewire.profile', [
            'datas' => $this->data,
        ])->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
