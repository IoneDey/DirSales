<div>
    <link href="{{ asset('css/style_alert_center_close.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styles_table_res.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/tabelsort.css') }}" rel="stylesheet" />

    <style>
        body,
        html {
            margin: 0;
            padding: 0;
        }

        /* Gaya umum untuk input-group dan input-group-item */
        .input-group {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            padding: 10px;
            background-color: #f0f0f0;
        }

        .input-group-item {
            flex: 1 1 300px;
            /* Menetapkan lebar item menjadi fleksibel dengan lebar minimum 300px */
            display: flex;
            flex-direction: column;
        }

        .input-label {
            margin-bottom: 5px;
            font-weight: normal;
        }

        .form-control {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            width: 100%;
            /* Menyesuaikan lebar input dengan lebar parent */
        }

        /* Media Queries untuk responsif */
        @media (max-width: 768px) {
            .input-group-item {
                flex: 1 1 100%;
                /* Item akan menjadi satu baris pada layar kecil */
            }
        }

        .custom-divider {
            height: 1px;
            background-color: blue;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 1);
            margin: 20px 0;
        }
    </style>

    <h2 class="text-center">{{ $title }}</h2>

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

    @if ($idswitchMenu >= 1)
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="container" style="border: 2px solid black; border-radius: 5px; padding: 3px; margin-bottom: 5px; {{ $idswitchMenu >= 1 && $idswitchItem == 1 ? 'display: none;' : '' }}">
                    <div class="row justify-content-left">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="row">
                                <a class="hide-button" wire:click="myswitch(1)" href="#" style="text-decoration: none;"><i class="fas fa-solid fa-angles-down"></i> Setup Kota dan Periode</a>
                            </div>
                            <div class="mb-1">
                                <p>{{ $timNamaAktif }} - {{ $timKotaAktif }} - ({{ $tglawal }}) - ({{ $tglakhir }})</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="container" style="border: 2px solid black; border-radius: 5px; padding: 3px; margin-bottom: 5px;{{ $idswitchItem == 1 ? '' : 'display: none;' }}">
                    <div class="row justify-content-center">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div>Setup Kota dan Periode</div>
                            <div class="input-group">
                                <div class="input-group-item">
                                    <span class="input-label">Tim</span>
                                    <select wire:model="timid" type="text" class="form-control">
                                        <option value=""></option>
                                        @foreach ($dbTims as $dbtTim)
                                        <option value="{{ $dbtTim->id }}">{{ $dbtTim->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('timid')
                                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-group-item">
                                    <span class="input-label">Kota</span>
                                    <select wire:model="kotaid" type="text" class="form-control">
                                        <option value=""></option>
                                        @foreach ($dbKotas as $dbKota)
                                        <option value="{{ $dbKota->id }}">{{ $dbKota->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('kotaid')
                                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-group-item">
                                    <span class="input-label">Tgl Awal</span>
                                    <input wire:model.live="tglawal" type="date" class="form-control">
                                    @error('tglawal')
                                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-group-item">
                                    <span class="input-label">Tgl Akhir</span>
                                    <input wire:model="tglakhir" type="date" class="form-control" disabled>
                                    @error('tglakhir')
                                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-group-item">
                                    <span class="input-label">Angsuran - Hari</span>
                                    <input wire:model="angsuranhari" type="number" class="form-control">
                                    @error('angsuranhari')
                                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-group-item">
                                    <span class="input-label">Angsuran - Periode</span>
                                    <input wire:model="angsuranperiode" type="number" class="form-control">
                                    @error('angsuranperiode')
                                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-group-item">
                                    <span class="input-label">P.I.C</span>
                                    <input wire:model="pic" type="text" class="form-control">
                                    @error('pic')
                                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                @if ($isUpdateTim)
                                <button wire:click="" type="button" class="btn btn-primary">Update</button>
                                @else
                                <button wire:click="createTimSetup" type="button" class="btn btn-primary">Simpan</button>
                                @endif
                                <button wire:click="clearTimSetup" type="button" class="btn btn-secondary">Bersihkan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($idswitchMenu >= 2)
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="container" style="border: 2px solid black; border-radius: 5px; padding: 3px; margin-bottom: 5px;{{ $idswitchMenu >= 2 && $idswitchItem == 2 ? 'display: none;' : '' }}">
                    <div class="row justify-content-left">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="row">
                                <a class="hide-button" wire:click="myswitch(2)" href="#paket" style="text-decoration: none;"><i class="fas fa-solid fa-angles-down"></i> Setup Paket</a>
                            </div>
                            <div>
                                <nav class="nav nav-pills nav-fill mb-1">
                                    @foreach ($dbdatapakets as $dbdatapaket)
                                    <a wire:click="getdataTimSetupPaket({{ $dbdatapaket->id }},false)" class="nav-link {{ $timIdAktifPaket == $dbdatapaket->id  ? 'active' : '' }} " aria-current="page" href="#detailbarang">{{ $dbdatapaket->nama }}</a>
                                    @endforeach
                                </nav>
                                {{ $dbdatapakets->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="container" style="border: 2px solid black; border-radius: 5px; padding: 3px; margin-bottom: 5px;{{ $idswitchItem == 2 ? '' : 'display: none;' }}">
                    <div class="row justify-content-center">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div>Setup Paket</div>
                            <div class="input-group">
                                <div class="input-group-item">
                                    <span class="input-label">Nama Paket</span>
                                    <input id="paket" wire:model="nama" type="text" class="form-control">
                                    @error('nama')
                                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="input-group-item">
                                    <span class="input-label">Harga Jual</span>
                                    <input wire:model="hargajual" type="number" class="form-control">
                                    @error('hargajual')
                                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    @if ($isUpdatePaket)
                                    <button wire:click="" type="button" class="btn btn-primary">Update</button>
                                    @else
                                    <button wire:click="createTimSetupPaket" type="button" class="btn btn-primary">Simpan</button>
                                    @endif
                                    <button wire:click="clearPaket" type="button" class="btn btn-secondary">Bersihkan</button>
                                </div>
                            </div>

                            <div class="custom-divider mt-2 mb-3"></div>

                            <table class="table table-sm table-hover table-striped table-bordered border-primary-subtle table-sortable mb-1">
                                <thead>
                                    <th>#</th>
                                    <th class="sort @if ($sortColumn=='timid') {{ $sortDirection }} @endif" wire:click="sort('timid')">Paket</th>
                                    <th class="sort @if ($sortColumn=='kotaid') {{ $sortDirection }} @endif" wire:click="sort('kotaid')">Harga Jual</th>
                                    <th>Act</th>
                                </thead>
                                <tbody>
                                    @foreach ($dbdatapakets as $dbdatapaket)
                                    <tr>
                                        <td>{{ (($dbdatapakets->currentPage()-1)*$dbdatapakets->perPage()) + $loop->iteration }}</td>
                                        <td>{{ $dbdatapaket->nama }}</td>
                                        <td>{{ $dbdatapaket->hargajual }}</td>
                                        <td>
                                            <a wire:click="getdataTimSetupPaket({{ $dbdatapaket->id }},true)" wire:loading.attr="disabled" type="button" class="badge bg-warning bg-sm" href="#top"><i class=" bi bi-pencil-fill"></i></a>
                                            <a wire:click="" wire:loading.attr="disabled" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#ModalDelete"><i class="bi bi-eraser"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $dbdatapakets->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($idswitchMenu >= 3)
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="container" style="border: 2px solid black; border-radius: 5px; padding: 3px; margin-bottom: 5px;{{ $idswitchMenu >= 3 && $idswitchItem == 3 ? 'display: none;' : '' }}">
                    <div class="row justify-content-left">
                        <div class="col-12 col-md-10 col-lg-8">
                            <div class="row">
                                <a class="hide-button" wire:click="myswitch(3)" href="#detailbarang" style="text-decoration: none;"><i class="fas fa-solid fa-angles-down"></i> Detail Barang</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="container" style="border: 2px solid black; border-radius: 5px; padding: 3px; margin-bottom: 5px;{{ $idswitchItem == 3 ? '' : 'display: none;' }}">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div>Detail Barang</div>
                            <div id="detailbarang" class="input-group">
                                <div class="input-group-item">
                                    <span class="input-label">Barang</span>
                                    <input type="text" class="form-control">
                                </div>
                                <div class="input-group-item">
                                    <span class="input-label">HPP</span>
                                    <input type="number" class="form-control">
                                </div>
                            </div>
                            <div>
                                @if ($isUpdateBarang)
                                <button wire:click="" type="button" class="btn btn-primary">Update</button>
                                @else
                                <button wire:click="createTimSetupPaket" type="button" class="btn btn-primary">Simpan</button>
                                @endif
                                <button wire:click="clearPaket" type="button" class="btn btn-secondary">Bersihkan</button>
                            </div>

                            <div class="custom-divider mt-2 mb-3"></div>

                            <table>
                                <thead>

                                </thead>
                            </table>

                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="custom-divider mt-2 mb-3"></div>

        <div class="col-12 mb-1">
            <input class="border rounded" wire:model.live.debounce.500ms="cari" type="text" id="cari" placeholder="cari....">
        </div>

        <div>
            <table class="table table-sm table-hover table-striped table-bordered border-primary-subtle table-sortable">
                <thead>
                    <th>#</th>
                    <th class="sort @if ($sortColumn=='timid') {{ $sortDirection }} @endif" wire:click="sort('timid')">Tim</th>
                    <th class="sort @if ($sortColumn=='kotaid') {{ $sortDirection }} @endif" wire:click="sort('kotaid')">Kota</th>
                    <th class="sort @if ($sortColumn=='tglawal') {{ $sortDirection }} @endif" wire:click="sort('tglawal')">Tgl Awal</th>
                    <th class="sort @if ($sortColumn=='tglakhir') {{ $sortDirection }} @endif" wire:click="sort('tglakhir')">Tgl Akhir</th>
                    <th class="sort @if ($sortColumn=='angsuranhari') {{ $sortDirection }} @endif" wire:click="sort('angsuranhari')">Angsuran-Hari</th>
                    <th class="sort @if ($sortColumn=='angsuranperiode') {{ $sortDirection }} @endif" wire:click="sort('angsuranperiode')">Angsuran-Periode</th>
                    <th>Act</th>
                </thead>
                <tbody>
                    @foreach ($dbdatas as $dbdata)
                    <tr>
                        <td>{{ (($dbdatas->currentPage()-1)*$dbdatas->perPage()) + $loop->iteration }}</td>
                        <td>{{ $dbdata->joinTim->nama }}</td>
                        <td>{{ $dbdata->joinKota->nama }}</td>
                        <td>{{ $dbdata->tglawal }}</td>
                        <td>{{ $dbdata->tglakhir }}</td>
                        <td>{{ $dbdata->angsuranhari }}</td>
                        <td>{{ $dbdata->angsuranperiode }}</td>
                        <td>
                            <a wire:click="editTimSetup({{ $dbdata->id }})" wire:loading.attr="disabled" type="button" class="badge bg-warning bg-sm" href="#top"><i class=" bi bi-pencil-fill"></i></a>
                            <a wire:click="confirmDelete({{ $dbdata->id }})" wire:loading.attr="disabled" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#ModalDelete"><i class="bi bi-eraser"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $dbdatas->links() }}
        </div>

    </div>

</div>