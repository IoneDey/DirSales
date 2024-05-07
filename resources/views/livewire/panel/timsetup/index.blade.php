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
            /* Menetapkan lebar item menjadi fleksibel dengan lebar minimum 300px */
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

    <h2 class="text-center">{{ $title }}</h2>

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

    @if ($idswitchMenu >= 1)
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12  col-md-8">
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
            <div class="col-12 col-md-8">
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
                                <div wire:ignore class="input-group-item select2-containe ">
                                    <span class="input-label">Kota</span>
                                    <select x-data="{
                                        item: @entangle('kotaid')}
                                        " x-init="$($refs.select2ref).select2();
                                            $($refs.select2ref).on('change', function(){$wire.set('kotaid', $(this).val());});
                                        " x-effect="
                                            $refs.select2ref.value = item;
                                            $($refs.select2ref).select2();
                                        " x-ref="select2ref">
                                        <option value=null></option>
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
                                <button wire:click="updateTimSetup" type="button" class="btn btn-primary">Update</button>
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
            <div class="col-12  col-md-8">
                <div class="container" style="border: 2px solid black; border-radius: 5px; padding: 3px; margin-bottom: 5px;{{ $idswitchMenu >= 2 && $idswitchItem == 2 ? 'display: none;' : '' }}">
                    <div class="row justify-content-left">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div class="row">
                                <a class="hide-button" wire:click="myswitch(2)" href="#paket" style="text-decoration: none;"><i class="fas fa-solid fa-angles-down"></i> Setup Paket</a>
                            </div>
                            <div>
                                <nav class="nav nav-pills nav-fill mb-1">
                                    @foreach ($dbdatapakets as $dbdatapaket)
                                    <a wire:click="editPaket({{ $dbdatapaket->id }},true)" class="nav-link {{ $timIdAktifPaket == $dbdatapaket->id  ? 'active' : '' }} " aria-current="page" href="#detailbarang">{{ $dbdatapaket->nama }} - H.Jual: {{ number_format($dbdatapaket->hargajual, 0, ',', '.') }}</a>
                                    @endforeach
                                </nav>
                                {{ $dbdatapakets->links(data: ['scrollTo' => false]) }}
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
            <div class="col-12 col-md-8">
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

                                <div class="input-group-item" x-data="{ hargaJual: @entangle('hargajual') }">
                                    <span class="input-label">Harga Jual</span>
                                    <input wire:model="hargajual" type="text" inputmode="text" class="form-control" x-model.lazy="hargaJual" x-on:input="formatAngka($event)">
                                    @error('hargajual')
                                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div>
                                    @if ($isUpdatePaket)
                                    <button wire:click="updatePaket" type="button" class="btn btn-primary">Update</button>
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
                                    <th class="sort @if ($sortColumn=='nama') {{ $sortDirection }} @endif" wire:click="sort('nama')">Paket</th>
                                    <th class="sort @if ($sortColumn=='hargajual') {{ $sortDirection }} @endif rata-kanan" wire:click="sort('hargajual')">Harga Jual</th>
                                    <th>Act</th>
                                </thead>
                                <tbody>
                                    @foreach ($dbdatapakets as $dbdatapaket)
                                    <tr>
                                        <td>{{ (($dbdatapakets->currentPage()-1)*$dbdatapakets->perPage()) + $loop->iteration }}</td>
                                        <td>{{ $dbdatapaket->nama }}</td>
                                        <td class="rata-kanan">{{ number_format($dbdatapaket->hargajual, 0, ',', '.') }}</td>
                                        <td>
                                            <a wire:click="editPaket({{ $dbdatapaket->id }},false)" wire:loading.attr="disabled" type="button" class="badge bg-warning bg-sm" href="#top"><i class=" bi bi-pencil-fill"></i></a>
                                            <a wire:click="confirmDeletePaket({{ $dbdatapaket->id }},false)" wire:loading.attr="disabled" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#ModalDeletePaket"><i class="bi bi-eraser"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $dbdatapakets->links(data: ['scrollTo' => false]) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($idswitchMenu >= 3)
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12  col-md-8">
                <div class="container" style="border: 2px solid black; border-radius: 5px; padding: 3px; margin-bottom: 5px;{{ $idswitchMenu >= 3 && $idswitchItem == 3 ? 'display: none;' : '' }}">
                    <div class="row justify-content-left">
                        <div class="col-12 col-md-10 col-lg-8">
                            <div class="row">
                                <a class="hide-button" wire:click="myswitch(3)" href="#detailbarang" style="text-decoration: none;"><i class="fas fa-solid fa-angles-down"></i> Detail Barang</a>
                            </div>
                            <div>
                                <p>Total barang: {{ $dbdatabarangs->count() }} - T. Hpp: {{ number_format($dbdatabarangs->sum('hpp') , 0, ',', '.') }}</p>
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
            <div class="col-12  col-md-8">
                <div class="container" style="border: 2px solid black; border-radius: 5px; padding: 3px; margin-bottom: 5px;{{ $idswitchItem == 3 ? '' : 'display: none;' }}">
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-12 col-lg-12">
                            <div>Detail Barang</div>
                            <div id="detailbarang" class="input-group">
                                <div wire:ignore class="input-group-item select2-containe ">
                                    <span class="input-label">Barang</span>
                                    <select x-data="{
                                        item: @entangle('barangid')}
                                        " x-init="$($refs.select2ref).select2();
                                            $($refs.select2ref).on('change', function(){$wire.set('barangid', $(this).val());});
                                        " x-effect="
                                            $refs.select2ref.value = item;
                                            $($refs.select2ref).select2();
                                        " x-ref="select2ref">
                                        <option value=""></option>
                                        @foreach ($dbBarangs as $dbBarang)
                                        <option value="{{ $dbBarang->id }}">{{ $dbBarang->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('barangid')
                                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="input-group-item" x-data="{ Hpp: @entangle('hpp') }">
                                    <span class="input-label">HPP</span>
                                    <input wire:model="hpp" type="text" inputmode="text" class="form-control" x-model="Hpp" x-on:input="formatAngka($event)">
                                    @error('hpp')
                                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                @if ($isUpdateBarang)
                                <button wire:click="updateBarang" type="button" class="btn btn-primary">Update</button>
                                @else
                                <button wire:click="createTimSetupBarang" type="button" class="btn btn-primary">Simpan</button>
                                @endif
                                <button wire:click="clearBarang" type="button" class="btn btn-secondary">Bersihkan</button>
                            </div>

                            <div class="custom-divider mt-2 mb-3"></div>

                            <table class="table table-sm table-hover table-striped table-bordered border-primary-subtle table-sortable mb-1">
                                <thead>
                                    <th>#</th>
                                    <th class="sort @if ($sortColumn=='barang') {{ $sortDirection }} @endif" wire:click="sort('nama')">Barang</th>
                                    <th class="sort @if ($sortColumn=='hargajual') {{ $sortDirection }} @endif rata-kanan" wire:click="sort('hargajual')">HPP</th>
                                    <th>Act</th>
                                </thead>
                                <tbody>
                                    @foreach ($dbdatabarangs as $dbdatabarang)
                                    <tr>
                                        <td>{{ (($dbdatabarangs->currentPage()-1)*$dbdatabarangs->perPage()) + $loop->iteration }}</td>
                                        <td>{{ $dbdatabarang->joinBarang->nama }}</td>
                                        <td class="rata-kanan">{{ number_format($dbdatabarang->hpp, 0, ',', '.') }}</td>
                                        <td>
                                            <a wire:click="editBarang({{ $dbdatabarang->id }})" wire:loading.attr="disabled" type="button" class="badge bg-warning bg-sm" href="#top"><i class=" bi bi-pencil-fill"></i></a>
                                            <a wire:click="confirmDeleteBarang({{ $dbdatabarang->id }})" wire:loading.attr="disabled" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#ModalDeleteBarang"><i class="bi bi-eraser"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
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
                            <a wire:click="confirmDeleteTim({{ $dbdata->id }})" wire:loading.attr="disabled" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#ModalDeleteTim"><i class="bi bi-eraser"></i></a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $dbdatas->links() }}
        </div>
    </div>

    <!-- untuk modal confirm delete barang-->
    <div wire:ignore.self class="modal fade" id="ModalDeleteBarang" tabindex="-1" aria-labelledby="ModalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalDeleteLabel">Hapus Data</h1>
                    <button wire:click="clearBarang" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin hapus data {{ $timBarangAktif }}?
                </div>
                <div class="modal-footer">
                    <button wire:click="clearBarang" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button wire:click="deleteBarang()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- untuk modal confirm delete paket-->
    <div wire:ignore.self class="modal fade" id="ModalDeletePaket" tabindex="-1" aria-labelledby="ModalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalDeleteLabel">Hapus Data</h1>
                    <button wire:click="clearPaket" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin hapus data {{ $nama }}?
                </div>
                <div class="modal-footer">
                    <button wire:click="clearPaket" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button wire:click="deletePaket()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- untuk modal confirm delete tim-->
    <div wire:ignore.self class="modal fade" id="ModalDeleteTim" tabindex="-1" aria-labelledby="ModalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalDeleteLabel">Hapus Data</h1>
                    <button wire:click="clearTimSetup" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin hapus data {{ $timNamaAktif }} - {{ $timKotaAktif }}
                    (SEMUA DATA PAKET DAN BARANG AKAN IKUT TERHAPUS) ?
                </div>
                <div class="modal-footer">
                    <button wire:click="clearTimSetup" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button wire:click="deleteTim()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>