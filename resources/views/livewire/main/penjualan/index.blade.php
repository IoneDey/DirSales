<div>
    <style>
        .form-floating .form-control {
            border-width: 1px;
            border-color: blue;
            border-radius: 5px;
            border-style: solid;
        }
    </style>

    <h2 class="text-center" id="top">{{ $title }}</h2>

    <div class="container">


        <form action="">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="form-floating mb-2">
                        <select wire:model.live="idNomer" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                            <option value=""></option>
                            @foreach ($dbTims as $dbTim)
                            <option value="{{ $dbTim->id }}">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>Tim: {{ $dbTim->nomer }}</td>
                                            <td>Kota: {{ $dbTim->joinKota->kota_kabupaten }}</td>
                                            <td>PT: {{ $dbTim->joinPT->nama }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </option>
                            @endforeach
                        </select>
                        <label for="floatingSelect">Pilih Tim</label>
                    </div>

                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="fnota" placeholder="name@example.com">
                        <label for="fnota">Nota</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="number" class="form-control" id="ftot" placeholder="name@example.com" min="0" step="500">
                        <label for="ftot">Total Tagihan Nota</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="date" class="form-control" id="ftot" placeholder="name@example.com">
                        <label for="ftot">Tgl Jual</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="ftot" placeholder="name@example.com">
                        <label for="ftot">Nama Sales</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="ftot" placeholder="name@example.com">
                        <label for="ftot">Nama Customer</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="ftot" placeholder="name@example.com">
                        <label for="ftot">Alamat Customer</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="ftot" placeholder="name@example.com">
                        <label for="ftot">No. Telp</label>
                    </div>


                    <table>
                        <tr>
                            <td>
                                <div class="form-floating mb-1">
                                    <select class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                        <option value=""></option>
                                        @foreach ($dbBarangTims as $dbBarangTim)
                                        <option value="{{ $dbBarangTim->id }}">
                                            Kode: {{ $dbBarangTim->joinBarang->kode }}
                                            Barang: {{ $dbBarangTim->joinBarang->nama }}
                                            HJual: {{ $dbBarangTim->hargajual }}
                                            Hpp: {{ $dbBarangTim->hpp }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <label for="floatingSelect">Pilih Barang</label>
                                </div>
                            </td>
                            <td>
                                <div class="form-floating mb-1">
                                    <input type="number" class="form-control" id="ftot" placeholder="name@example.com">
                                    <label for="ftot">Jml. Terjual</label>
                                </div>
                            </td>
                            <td>
                                <a class="badge bg-warning bg-sm"><i class="bi bi-pencil-fill"></i></a>
                                <a class="badge bg-danger bg-sm"><i class="bi bi-eraser"></i></a>
                            </td>
                        </tr>
                    </table>



                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="ftot" placeholder="name@example.com">
                        <label for="ftot">Satuan</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="ftot" placeholder="name@example.com">
                        <label for="ftot">Foto Nota</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="ftot" placeholder="name@example.com">
                        <label for="ftot">Foto Ktp</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="ftot" placeholder="name@example.com">
                        <label for="ftot">Tim Lock</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="ftot" placeholder="name@example.com">
                        <label for="ftot">Share Loc</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="ftot" placeholder="name@example.com">
                        <label for="ftot">Penanggung Jawab Kolektor</label>
                    </div>
                    <div class="form-floating mb-1">
                        <input type="text" class="form-control" id="ftot" placeholder="name@example.com">
                        <label for="ftot">Nama Supir</label>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>