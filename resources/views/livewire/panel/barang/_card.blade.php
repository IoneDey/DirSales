<div wire:key="{{ $dbdata->id }}">
    <div class="custom-divider mt-2 mb-3"></div>

    <h3>Nama Barang: {{ $dbdata->nama }}</h3>
    <input wire:model="nama" type="text">

    <a wire:click="edit({{ $dbdata->id }})" wire:loading.attr="disabled" type="button" class="badge bg-warning bg-sm" href="#top"><i class=" bi bi-pencil-fill"></i></a>
    <a wire:click="confirmDelete({{ $dbdata->id }})" wire:loading.attr="disabled" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#ModalDelete"><i class="bi bi-eraser"></i></a>

</div>
