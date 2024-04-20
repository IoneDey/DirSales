<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use Livewire\Component;

//#[\Livewire\Attributes\layout('layouts.main-layout')]
class Login extends Component
{
    public string $title = 'Login';
    public string $username = '';
    public string $password = '';

    public function login()
    {
        //dd($this);
        if (Auth::attempt($this->only('username', 'password'))) {
            return redirect(route('main'));
        }

        throw ValidationException::withMessages([
            'password' => 'Login gagal.'
        ]);
    }

    public function render()
    {
        return view('livewire.login')->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
