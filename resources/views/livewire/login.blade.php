<div>
    <div class="container">
        <div class="row min-vh-100 align-items-top justify-content-start">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body">
                        <form wire:submit='login()'>
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
                            <button type="submit" class="btn btn-primary">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>