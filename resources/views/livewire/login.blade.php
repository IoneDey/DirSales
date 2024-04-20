<div>
    <div class="container d-flex justify-content-center align-items-center  vh-100" style="max-height: 90vh;">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <form wire:submit='login()'>
                        <h1>Login</h1>
                        <div class="mb-4">
                            <label for="user" class="form-label">User Name</label>
                            <input wire:model="username" type="text" name="username" id="username" class="form-control">
                            @error('username')
                            <small class="d-block mt-1 text-danger"> {{ $message }} </small>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input wire:model="password" type="password" name="password" id="password" class="form-control">
                            @error('password')
                            <small class="d-block mt-1 text-danger"> {{ $message }} </small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Ok</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>