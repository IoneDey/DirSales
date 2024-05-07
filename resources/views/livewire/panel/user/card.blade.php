<div class="row justify-content-center">
    <div wire:key="{{ $data->id }}" class="card mb-1" style="max-width: 540px;">

        <button wire:key="{{ $data->name }}" wire:click="confirmDelete({{ $data->id }})" type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#ModalDelete" style="position: absolute; top: 5px; right: 5px;">X</button>

        <div class="row justify-content-left g-0 p-0 m-0">
            <div class="col-md-4">
                @if ($data->image)
                <img src="{{ asset('storage/' . $data->image ) }}" class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 100%;" alt="Profile Picture">
                @else
                <img src="{{ asset('img/profile-kosong.webp') }}" class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 100%;" alt="Profile Picture">
                @endif
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <h6 style="font-weight: bold; font-style: italic;">#{{ (($datas->currentPage()-1)*$datas->perPage()) + $loop->iteration }}</h6>
                    <h5 class="card-title">{{ $data->name }}</h5>
                    <p class="card-text">{{ $data->email  }}</p>
                    <p class="card-text">{{ $data->roles  }}</p>
                    <p class="card-text"><small class="text-body-secondary">{{ $data->username  }}</small></p>

                    <a wire:click="edit({{ $data->id }})" type="button" href="#top" class="rounded bg-primary text-white" style="text-decoration: none; padding-right: 7px; padding-left: 7px;">Update</a>
                    <a data-bs-toggle="modal" data-bs-target="#ModalPassword" type="button" class="rounded bg-info text-white" style="text-decoration: none; padding-right: 7px; padding-left: 7px;">Ubah Password</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- untuk modal confirm delete -->
<div wire:ignore.self class="modal fade" id="ModalDelete" tabindex="-1" aria-labelledby="ModalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalDeleteLabel">Hapus Data</h1>
                <button wire:click="clear" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Anda yakin hapus data?
            </div>
            <div class="modal-footer">
                <button wire:click="clear" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button wire:click="delete()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- untuk modal ubah password -->
<div wire:ignore.self class="modal fade" id="ModalPassword" tabindex="-1" aria-labelledby="ModalDeleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="ModalPasswordLabel">Ubah Password</h1>
                <button wire:click="clear" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                UNDER CONSTRUCTION
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
            </div>
        </div>
    </div>
</div>