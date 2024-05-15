<div>
    {{-- Success is as dangerous as failure. --}}
    <h2 class="text-center">{{ $title }}</h2>
    <div class="container content-wrapper">
        <div class="d-flex flex-column justify-content-center align-items-center">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-4 d-flex flex-column align-items-center">
                        <div class="mb-3">
                            <label class="form-label" for="inputGroupFile01">Profile Pic</label>
                            <input wire:model.live="image" accept="image/png, image/jpeg" type="file" class="form-control" id="inputGroupFile01">
                            @error('image')
                            <span class="text-danger" style="font-size: smaller;">{{ $message }}</span>
                            @enderror
                            <div wire:loading wire:target="image">Uploading...</div>
                        </div>
                        @if (is_string($data->image) && strlen($data->image) > 0)
                        <img src="{{ asset('storage/' . $image) }}" class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 100%;" alt="...">
                        @else
                        @if ($image)
                        <img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 100%;" alt="...">
                        @else
                        <img src="{{ asset('img/profile-kosong.webp') }}" class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 100%;" alt="Profile Picture">
                        @endif
                        @endif
                    </div>
                    <div class="col-md-4 d-flex justify-content-center align-items-center">
                        <div class="card-body">
                            <h5 class="card-title">Nama: {{ $data->name }}</h5>
                            <p class="card-text">Email: {{ $data->email }}</p>
                            <p class="card-text">Roles: {{ $data->roles }}</p>
                            <p class="card-text"><small class="text-body-secondary">Username: {{ $data->username }}</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>