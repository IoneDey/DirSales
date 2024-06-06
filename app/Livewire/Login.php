<?php

namespace App\Livewire;

use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

//#[\Livewire\Attributes\layout('layouts.main-layout')]
class Login extends Component {
    public string $title = 'Login';
    public string $username = '';
    public string $password = '';

    public $secondsRemaining = 0;
    public $timerStart = false;
    public $timerNotLogin = 0;

    public function resetErrors() {
        $this->resetErrorBag();
    }

    public function countDown() {
        if ($this->timerStart) {
            $this->secondsRemaining = RateLimiter::availableIn($this->throttleKey());
            if ($this->secondsRemaining == 0) {
                $this->timerStart = false;
                $this->resetErrors();
            }
        }
        $this->timerNotLogin++;
        if ($this->timerNotLogin > 120) {
            $this->timerNotLogin = 0;
            return redirect(route('main'));
        }
    }

    public function throttlekey() {
        return Str::lower($this->username) . '|' . request()->ip();
    }

    public function login() {
        try {
            $this->timerNotLogin = 0;
            $throttleKey = $this->throttlekey();
            Ratelimiter::hit($throttleKey);
            if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
                $this->timerStart = true;
                throw ValidationException::withMessages([
                    'username' => 'Anda telah mencoba login terlalu banyak.'
                ]);
            }

            $credentials = $this->only('username', 'password');
            if (!Auth::attempt($credentials)) {
                throw ValidationException::withMessages([
                    'password' => 'Kombinasi nama pengguna dan kata sandi tidak valid.'
                ]);
            }
            RateLimiter::clear($throttleKey);
            return redirect(route('main'));
        } catch (ValidationException $e) {
            // Tangani pengecualian validasi
            throw $e;
        } catch (\Exception $e) {
            // Tangani pengecualian umum
            throw ValidationException::withMessages([
                'password' => 'Login gagal.'
            ]);
        }
    }


    // public function login() {
    //     try {
    //         $this->checkTooManyFaildAttempts($request->username);
    //         $cradentials = $request->only('username', 'password');
    //         if (!Auth::attempt($cradentials)) {
    //             Ratelimiter::hit($this->throttlekey($requesr->username));
    //             throw Exception('Invalid cradentials. Attempt remaining: ' . (10 - Ratelimiter::attempts($this->throttlekey($request->username))));
    //         }
    //         RateLimiter::clear($this->throttlekey($request->username));
    //         return respone()->json(['status' => 1]);
    //     } catch (Exception $error) {
    //         return respone()->json(['status' => 3, 'error' => $error->getMessage()]);
    //     }

    //     if (Auth::attempt($this->only('username', 'password'))) {
    //         return redirect(route('main'));
    //     }
    //     throw ValidationException::withMessages([
    //         'password' => 'Login gagal.'
    //     ]);
    // }

    public function render() {

        return view('livewire.login')->layout('layouts.app-layout', [
            'menu' => 'navmenu.main',
            'title' => $this->title,
        ]);
    }
}
