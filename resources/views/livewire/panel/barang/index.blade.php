<div>
    <style>
        /* CSS untuk menambahkan efek bayangan pada form */
        form {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class='container'>
        @if(session()->has('ok'))
        <div class="alert alert-danger" id="ok-message">
            {{ session('ok') }}
        </div>
        <script>
            setTimeout(function() {
                var errorMessage = document.getElementById('ok-message');
                if (errorMessage) {
                    errorMessage.remove();
                }
            }, 3500);
        </script>
        @endif
        <form class='mb-1 p-2'>
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="form-floating mb-2">
                        <input wire:model="kode" type="text" class="form-control @error('kode') is-invalid @enderror" id="floatingInputKode" placeholder=" ">
                        <label for="floatingInput1">Kode</label>
                        @error('kode')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="form-floating mb-1">
                        <input wire:model="nama" type="text" class="form-control @error('nama') is-invalid @enderror" id="floatingInput2" placeholder=" ">
                        <label for="floatingInput2">Nama</label>
                        @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-2 d-flex justify-content-start align-items-center">
                    @if ( $isUpdate == false)
                    <button wire:loading.attr="disabled" type="button" name='btnsimpan' class="btn btn-primary mr-5 w-150" wire:click="store()">Simpan</button>
                    @else
                    <button type="button" name='btnupdate' class="btn btn-primary mr-5 w-150" wire:click="update()">Update</button>
                    @endif
                    <span>.</span>
                    <button type="button" name='btnclear' class="btn btn-secondary mr-5 w-150" wire:click="clear()">Clear</button>
                </div>
            </div>
        </form>


        <div>
            <form class='p-2'>
                <div class="row">
                    <h3>Daftar Barang</h>
                </div>

                <div class="row">
                    <div class="col-md-2 position-relative d-flex align-items-end">
                        @if ($selected_id)
                        <a wire:click="delete_confirm('')" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#exampleModal" style="text-decoration: none; cursor: pointer;"><i class="bi bi-eraser"></i>Hapus {{ count($selected_id) }} data.</a>
                        @endif
                    </div>
                    <div class="col-md-3 offset-md-7">
                        <div class="form-floating">
                            <input wire:model.live="textcari" style="width: 100%;" type="text" name="search" id="floatingInputcari" placeholder=" " class="form-control mb-2">
                            <label for="floatingInputcari"> Cari</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12">

                        {{ $dataBarang->links() }}

                        <table class="table table-sm table-striped table-hover table-sortable">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>#</th>
                                    <th class="sort @if ($sortColumn=='kode') {{ $sortDirection }} @endif" wire:click="sort('kode')">Kode</th>
                                    <th class="sort @if ($sortColumn=='nama') {{ $sortDirection }} @endif" wire:click="sort('nama')">Nama</th>
                                    <th>User Id</th>
                                    <th class="rata-kanan">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $dataBarang as $barang )
                                <tr>
                                    <td><input wire:key="{{ $barang->id }}" wire:model.live="selected_id" value="{{ $barang->id }}" type="checkbox"></td>
                                    <td>{{ (($dataBarang->currentPage()-1)*$dataBarang->perPage()) + $loop->iteration }}</td>
                                    <td>{{ $barang->kode }}</td>
                                    <td>{{ $barang->nama }}</td>
                                    <td>{{ $barang->joinUser->name }}</td>
                                    <td class="rata-kanan">
                                        <a wire:click="edit({{ $barang->id }})" class="badge bg-warning bg-sm"><i class="bi bi-pencil-fill"></i></a>
                                        <a wire:click="delete_confirm({{ $barang->id }})" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-eraser"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin hapus data {{ $nama }}?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button wire:click="delete()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>