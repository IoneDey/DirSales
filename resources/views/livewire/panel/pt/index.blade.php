<div>
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

    <div class='container'>
        <form class="shadow-lg p-3 mb-1" action="">
            @csrf
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="form-floating mb-2">
                        <input wire:model="kode" autofocus type="text" class="form-control @error('kode') is-invalid @enderror" id="floatingInput1" placeholder=" ">
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
            </div>

            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <div class="form-floating mb-2">
                        <input wire:model="angsuranhari" type="number" class="form-control @error('angsuranhari') is-invalid @enderror" id="floatingInput3" placeholder=" ">
                        <label for="floatingInput3">Angsuran - Hari</label>
                        @error('angsuranhari')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="form-floating mb-2">
                        <input wire:model="angsuranperiode" type="number" class="form-control @error('angsuranperiode') is-invalid @enderror" id="floatingInput4" placeholder=" ">
                        <label for="floatingInput4">Angsuran - Periode</label>
                        @error('angsuranperiode')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="col-sm-12 col-md-2 d-flex justify-content-center align-items-center">
                    <button type="button" name='btnsimpan' class="btn btn-primary" wire:click="store()">Simpan</button>
                </div>
            </div>
        </form>

        <form class="shadow-lg p-3 mb-1" action="">
            <div class="col-sm-12">
                {{ $dataPT->links() }}
                <table class="table table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Kode</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Angsuran-Hari</th>
                            <th scope="col">Angsuran-Periode</th>
                            <th class="rata-kanan" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $dataPT as $pt )
                        <tr>
                            <th scope="row">{{ $loop->iteration }}</th>
                            <td>{{ $pt->kode }}</td>
                            <td>{{ $pt->nama }}</td>
                            <td class="rata-kanan">{{ $pt->angsuranhari }}</td>
                            <td class="rata-kanan">{{ $pt->angsuranperiode }}</td>
                            <td class="rata-kanan">
                                <a class="badge bg-warning" href="#"><i class="bi bi-pencil-fill"></i></a>
                                <form action="" method="POST" style="display: inline;">
                                    <button type="submit" class="badge bg-danger border-0" onclick="return confirm('Apakah Anda yakin ingin menghapus data?')"><i class="bi bi-eraser"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach


                    </tbody>
                </table>

            </div>
        </form>
    </div>
</div>