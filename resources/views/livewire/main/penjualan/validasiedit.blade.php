<div>
    <link href="{{ asset('css/style_alert_center_close.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styles_table_res.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/tabelsort.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styleSelect2.css') }}" rel="stylesheet" />
    <script src="{{ asset('js/formatAngka.js') }}"></script>

    <style>
        @media (max-width: 768px) {
            .input-group-item {
                flex: 1 1 100%;
                /* Item akan menjadi satu baris pada layar kecil */
            }
        }

        .input-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 10px;
            background-color: #f0f0f0;
            box-shadow: none !important;
        }

        .input-group-item {
            flex: 1 1 300px;
            display: flex;
            flex-direction: column;
            box-shadow: none !important;
            border-color: black;
        }

        .input-label {
            margin-bottom: 1px;
            font-weight: normal;
        }

        .custom-divider {
            height: 1px;
            background-color: blue;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 1);
            margin: 20px 0;
        }
    </style>

    <div class="container col-12" style="padding: 3px;">
        <h2 class="text-center">{{ $title }}</h2>
    </div>
    @if ($errors->any())
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <ul>
            @foreach ($errors->all() as $error)
            <pre>{{ $error }}</pre>
            @endforeach
        </ul>
        <button wire:click="resetErrors" type="button" class="btn-close" data-bs-dismiss="alert" aria-label=""></button>
    </div>
    @endif

    @if(session()->has('ok'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <ul>
            <pre>{{ session('ok') }} </pre>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label=""></button>
    </div>
    @endif

    @if(session()->has('error'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <ul>
            <pre>{{ session('error') }} </pre>
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label=""></button>
    </div>
    @endif

    <!-- header nota -->
    <div class="container col-10" style="padding: 3px; margin-bottom: 5px;">
        <a typ="button" class="badge bg-warning bg-sm mb-3" style="text-decoration: none;" href="{{ route('penjualanvalidasi') }}"><i class="bi bi-arrow-left-circle"></i> Kembali</a>

        <div class="input-group">
            <div x-data="{ isUpdate: @entangle('isUpdate') }" wire:ignore class="input-group-item select2-container">
                <span class="input-label">Tim</span>
                <select x-data="{
                        item: @entangle('timsetupid')}
                        " x-init="$($refs.select2ref).select2();
                            $($refs.select2ref).on('change', function(){$wire.set('timsetupid', $(this).val());});
                        " x-effect="
                            $refs.select2ref.value = item;
                            $($refs.select2ref).select2();
                        " x-ref="select2ref" :disabled="isUpdate">
                    <option value=""></option>
                    @foreach ($dbTimsetups as $dbTimsetup)
                    <option value="{{ $dbTimsetup->id }}">{{ $dbTimsetup->joinTim->nama }} ({{ $dbTimsetup->joinTim->joinPt->nama }}) - {{ $dbTimsetup->joinKota->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="input-group-item">
                <span class="input-label">Nota</span>
                <input wire:model="nota" type="text" class="form-control" {{ $roles == 'LOCK' ? 'disabled' : '' }}>
            </div>

            <div class="input-group-item">
                <span class="input-label">Angsuran - Hari</span>
                <input wire:model="angsuranhari" type="number" class="form-control">
            </div>

            <div class="input-group-item">
                <span class="input-label">Angsuran - Periode</span>
                <input wire:model="angsuranperiode" type="number" class="form-control">
            </div>

            <div class="input-group-item">
                <span class="input-label">Kecamatan</span>
                <input wire:model="kecamatan" type="text" class="form-control">
            </div>

            <div class="input-group-item">
                <span class="input-label">Tgl Jual</span>
                <input wire:model="tgljual" type="date" class="form-control">
            </div>
        </div>
    </div>

    <!-- entry customer -->
    <div class="container col-10" style="padding: 3px; margin-bottom: 5px;">
        <div class="input-group">
            <div class="input-group-item">
                <span class="input-label">Nama Customer</span>
                <input wire:model="customernama" type="text" class="form-control">
            </div>
            <div class="input-group-item">
                <span class="input-label">Alamat Customer</span>
                <input wire:model="customeralamat" type="text" class="form-control">
            </div>
            <div class="input-group-item">
                <span class="input-label">No.Telp Customer</span>
                <input wire:model="customernotelp" type="text" class="form-control">
            </div>
            <div class="input-group-item">
                <span class="input-label">Share Lokasi</span>
                <input wire:model="shareloc" type="text" class="form-control">
            </div>
        </div>
    </div>

    <!-- sales driver lock dll -->
    <div class="container col-10" style="padding: 3px; margin-bottom: 0px;">
        <div class="input-group">
            <div class="input-group-item">
                <span class="input-label">Nama Sales</span>
                <input wire:model="namasales" type="text" class="form-control">
            </div>

            <div class="input-group-item">
                <span class="input-label">Nama Lock</span>
                <input wire:model="namalock" type="text" class="form-control">
            </div>

            <div class="input-group-item">
                <span class="input-label">Nama Driver</span>
                <input wire:model="namadriver" type="text" class="form-control">
            </div>

            <div class="input-group-item">
                <span class="input-label">Penanggung jawab Kolektor Nota</span>
                <input wire:model="pjkolektornota" type="text" class="form-control">
            </div>

            <div class="input-group-item">
                <span class="input-label">Penanggung jawab Admin Nota</span>
                <input wire:model="pjadminnota" type="text" class="form-control">
            </div>
        </div>
    </div>

    <!-- upload ktp,nota dan notarekap -->
    <div class="container col-10" style="padding: 3px; margin-bottom: 0px;">
        <div class="input-group">
            <div class="input-group-item mb-0">
                <span class="input-label" for="inputGroupKTP">Foto KTP</span>
                <input wire:model="fotoktp" accept="image/png, image/jpeg" type="file" class="form-control" id="inputGroupKTP">
                <div wire:loading wire:target="fotoktp">Uploading...</div>
                @if (is_string($fotoktp) && strlen($fotoktp) > 0)
                <img src="{{ asset('storage/' . $fotoktp) }}" class="img-fluid rounded mx-auto d-block mt-2" alt="...">
                @else
                @if ($fotoktp)
                <img src="{{ $fotoktp->temporaryUrl() }}" class="img-fluid rounded mx-auto d-block mt-2" alt="...">
                @endif
                @endif
            </div>
            <div class="input-group-item mb-0">
                <span class="input-label" for="inputGroupNota">Foto Nota</span>
                <input wire:model="fotonota" accept="image/png, image/jpeg" type="file" class="form-control" id="inputGroupNota">
                <div wire:loading wire:target="fotonota">Uploading...</div>
                @if (is_string($fotonota) && strlen($fotonota) > 0)
                <img src="{{ asset('storage/' . $fotonota) }}" class="img-fluid rounded mx-auto d-block mt-2" alt="...">
                @else
                @if ($fotonota)
                <img src="{{ $fotonota->temporaryUrl() }}" class="img-fluid rounded mx-auto d-block mt-2" alt="...">
                @endif
                @endif
            </div>
            <div class="input-group-item mb-0">
                <span class="input-label" for="inputGroupNota">Foto Rekap Sales</span>
                <input wire:model="fotonotarekap" accept="image/png, image/jpeg" type="file" class="form-control" id="inputGroupNotaRekap">
                <div wire:loading wire:target="fotonotarekap">Uploading...</div>
                @if (is_string($fotonotarekap) && strlen($fotonotarekap) > 0)
                <img src="{{ asset('storage/' . $fotonotarekap) }}" class="img-fluid rounded mx-auto d-block mt-2" alt="...">
                @else
                @if ($fotonotarekap)
                <img src="{{ $fotonotarekap->temporaryUrl() }}" class="img-fluid rounded mx-auto d-block mt-2" alt="...">
                @endif
                @endif
            </div>
        </div>
        @if ($isUpdate)
        <button wire:click="update" type="button" class="btn btn-primary mt-1">Update</button>
        @else
        <button wire:click="create" type="button" class="btn btn-primary mt-1">Simpan</button>
        @endif
    </div>

    <!-- detail entry paket -->
    <div class="container col-10" style="padding: 3px;">
        <h6 style="margin-Top: 7px; margin-Bottom: 0px;"> Detail Barang</h6>
        <!-- entry paket -->
        <div class="container" style="padding: 3px; margin-bottom: 5px;">
            <div class="row mb-1">
                <div class='col-4'>
                    <div class="input-group-item">
                        <span class="input-label">Barang (Paket)</span>
                        <select wire:model="timsetuppaketid" class="form-control" {{ $isUpdatePaket ? 'disabled' : '' }}>
                            <option value=""></option>
                            @foreach ($dbTimssetuppakets as $dbTimssetuppaket)
                            <option value="{{ $dbTimssetuppaket->id }}">{{ $dbTimssetuppaket->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class='col-4'>
                    <div class="input-group-item">
                        <span class="input-label">Jumlah Barang</span>
                        <input wire:model="jumlah" type="number" class="form-control" disabled>
                    </div>
                </div>

                <div class='col-4'>
                    <div class="input-group-item">
                        <span class="input-label">Jumlah Koreksi Barang</span>
                        <input wire:model="jumlahkoreksi" type="number" class="form-control" {{ $isUpdate ? '' : 'disabled' }} placeholder="masukkan nilai yg benar">
                    </div>
                </div>
            </div>
            @if ($isUpdatePaket)
            <button wire:click="updatePaket" type="button" class="btn btn-primary mt-1" {{ $isUpdate ? '' : 'disabled' }}>Update</button>
            @else
            <button wire:click="createPaket" type="button" class="btn btn-primary mt-1" {{ $isUpdate ? '' : 'disabled' }}>Simpan</button>
            @endif
            <button wire:click="clearPaket" type="button" class="btn btn-secondary mt-1" {{ $isUpdate ? '' : 'disabled' }}>Bersihkan</button>
        </div>

        <div class="custom-divider mt-2 mb-3"></div>

        <!-- list paket -->
        <table class="table table-sm table-hover table-striped table-bordered border-primary-subtle">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Paket</th>
                    <th scope="col">Jumlah</th>
                    <th scope="col">Jumlah Koreksi</th>
                    <th scope="col" class="rata-kanan">H.Jual</th>
                    <th scope="col">Barang</th>
                    <th scope="col">Act</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dbPenjualandts as $dbPenjualandt)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $dbPenjualandt->joinTimSetupPaket->nama }}</td>
                    <td>{{ $dbPenjualandt->jumlah }}</td>
                    <td>{{ $dbPenjualandt->jumlah + $dbPenjualandt->jumlahkoreksi }}</td>
                    <td class="rata-kanan">{{ number_format($dbPenjualandt->joinTimSetupPaket->hargajual, 0, ',', '.') }}</td>
                    <td>
                        <ul style="list-style-type: none; padding-left: 0;">
                            @foreach ($dbPenjualandt->joinTimSetupPaket->joinTimSetupBarang as $joinTimSetupBarang)
                            <li>&bull; {{ $joinTimSetupBarang->joinBarang->nama }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <a wire:click="editPaket({{ $dbPenjualandt->id }})" wire:loading.attr="disabled" type="button" class="badge bg-warning bg-sm"><i class="bi bi-pencil-fill"></i></a>
                        <a wire:click="confirmDeletePaket({{ $dbPenjualandt->id }})" wire:loading.attr="disabled" type="button" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#ModalDeletePaket"><i class="bi bi-eraser"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <a typ="button" class="badge bg-warning bg-sm" style="text-decoration: none;" href="{{ route('penjualanvalidasi') }}"><i class="bi bi-arrow-left-circle"></i> Kembali</a>
    </div>

    <!-- modal delete paket -->
    <div wire:ignore.self class="modal fade" id="ModalDeletePaket" tabindex="-1" aria-labelledby="ModalDeleteLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalDeleteLabel">Hapus Data</h1>
                    <button wire:click="clearPaket" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin hapus data {{ $paketnama }}?
                </div>
                <div class="modal-footer">
                    <button wire:click="clearPaket" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button wire:click="deletePaket()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>