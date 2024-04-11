<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Logout extends Component
{

    public function logout()
    {
        Auth::logout();
        Request()->session()->invalidate();
        Request()->session()->regenerateToken();
        return redirect('/');
    }

    public function render()
    {
        return view('livewire.logout');
    }
}
