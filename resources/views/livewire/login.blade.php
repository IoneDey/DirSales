<div>
    <div class="container d-flex justify-content-center align-items-center  vh-100" style="max-height: 90vh;">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form wire:submit='login()'>
                        <h1>Login</h1>
                        <div class="mb-4">
                            <label class="form-label">User Name</label>
                            <input wire:model="username" type="text" name="username" id="username" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Password</label>
                            <input wire:model="password" type="password" name="password" id="password" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Login</button>
                        @error('password')
                        <small class="d-block mt-1 text-danger"> {{ $message }}</small>
                        @enderror
                        @error('username')
                        <small class="d-block mt-1 text-danger"> {{ $message }} Silakan coba lagi dalam {{ $secondsRemaining }} detik.</small>
                        @enderror
                    </form>
                </div>
            </div>
        </div>
    </div>
    @script
    <script>
        setInterval(() => {
            $wire.call('countDown')
        }, 1000)
    </script>
    @endscript
</div>