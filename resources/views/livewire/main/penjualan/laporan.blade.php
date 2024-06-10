<div>
    <link href="{{ asset('css/styles_table_res.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/style_alert_center_close.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styleSelect2.css') }}" rel="stylesheet" />

    <style>
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

        .custom-hover:hover {
            background-color: black !important;
            color: white;
        }

        .custom-hover:hover svg {
            fill: white;
        }

        /* untuk tabel scroll */
        table th,
        table td {
            white-space: nowrap;
        }

        .table-responsive {
            max-height: 66vh;
            overflow-y: auto;
            overflow-x: auto;
        }

        @media (max-height: 800px) {
            .table-responsive {
                max-height: 42vh;
            }
        }

        th {
            position: sticky;
            top: 0 !important;
            background-color: #f8f9fa !important;
            z-index: 10;
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4) !important;
        }

        td {
            position: relative !important;
            z-index: 1 !important;
        }
    </style>

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
    <h2 class="text-center">{{ $title }}</h2>

    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-3 col-12 mb-1 p-1 g-0">
                <div class="input-group">
                    <span class="input-group-text">Tgl Awal</span>
                    <input wire:model.live.debounce.500ms="tglAwal" type="date" class="form-control" aria-label="Tgl Awal">
                </div>
            </div>

            <div class="col-md-3 col-12 mb-1 p-1 g-0">
                <div class="input-group">
                    <span class="input-group-text">Tgl Akhir</span>
                    <input wire:model.live.debounce.500ms="tglAkhir" type="date" class="form-control" aria-label="Tgl Akhir">
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-3 col-12 mb-1 p-1 g-0">
                <div class="d-flex align-items-left" x-data="{ isUpdate: @entangle('isUpdate') }" wire:ignore>
                    <span class="me-0 input-group-text" style="padding: 0.375rem 0.5rem; border-radius: 0.25rem 0 0 0; margin-right: -0.5rem; height: 38px;">Tim</span>
                    <select x-data="{item: @entangle('timsetupid')}" x-init="$($refs.select2ref).select2(); $($refs.select2ref).on('change', function(){$wire.set('timsetupid', $(this).val());});" x-effect="$refs.select2ref.value = item; $($refs.select2ref).select2();" x-ref="select2ref" :disabled="isUpdate" class="form-select" aria-label="Tim">
                        <option value='Semua'>Semua</option>
                        @foreach ($dbTimsetups as $dbTimsetup)
                        <option value="{{ $dbTimsetup->id }}">{{ $dbTimsetup->joinTim->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @error('timsetupid')
                <span style="font-size: smaller; color: red;">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-3 col-12 mb-1 p-1 g-0">
                <div class="input-group">
                    <span class="input-group-text">Status</span>
                    <select wire:model.live.debounce.500ms="status" class="form-control" aria-label="Status">
                        <option value='Semua'>Semua</option>
                        <option value="Entry">Entry</option>
                        <option value="Entry Valid">Entry Valid</option>
                        <option value="Lock Valid">Lock Valid</option>
                        <option value="Valid">Valid</option>
                    </select>
                </div>
            </div>
        </div>

    </div>

    <div class="container">
        <div class="col-12 mt-1 mb-1">
            <input class="border rounded" wire:model.live.debounce.500ms="cari" type="text" id="cari" placeholder="cari nota/nama/no.telp ....">
        </div>

        <div class="table-responsive mb-1">
            <table class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        @if ((auth()->user()->roles ?? '')== 'SUPERVISOR')
                        <th>Sheet</th>
                        @endif
                        <th>Tim</th>
                        <th>Kota</th>
                        <th>Kecamatan</th>
                        <th>Nota</th>
                        <th>Tgl Jual</th>
                        <th>Nama Customer</th>
                        <th>Alamat Customer</th>
                        <th>Telp Customer</th>
                        <th>Nama Sales</th>
                        <th>Nama Lock</th>
                        <th>Nama Driver</th>
                        <th>PJ Kolektor Nota</th>
                        <th>PJ Admin Nota</th>
                        <th class="rata-kanan">Tot Jumlah</th>
                        <th>Barang</th>
                        <th>Foto KTP</th>
                        <th>Foto Nota</th>
                        <th>Foto Rekap Nota</th>
                        <th>Status</th>
                        <th>User Entry</th>
                        <th>Timestamp</th>
                        <th>User Lock</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualanhds as $penjualanhd)
                    <tr>
                        @if ((auth()->user()->roles ?? '')== 'SUPERVISOR')
                        <td class="rata-tengah">
                            @if ($penjualanhd->sheet)
                            <input type="checkbox" onclick="return false;" checked>
                            @else
                            <button wire:click="confirmUploadToSpreadsheet('{{ $penjualanhd->timsetupid }}','{{ $penjualanhd->nota }}')" wire:loading.attr="disabled" type="button" class="badge bg-success bg-sm" data-bs-toggle="modal" data-bs-target="#ModalUploadSheet" {{ $btnDisables ? 'disabled':'' }}><i class="bi bi-cloud-arrow-up" title="Upload data ke spreadsheet"></i></button>
                            @endif
                        </td>
                        @endif
                        <td>{{ $penjualanhd->joinTimSetup->jointim->nama }}</td>
                        <td>{{ $penjualanhd->joinTimSetup->joinkota->nama }}</td>
                        <td>{{ $penjualanhd->kecamatan }}</td>
                        <td>{{ $penjualanhd->nota }}</td>
                        <td>{{ $penjualanhd->tgljual }}</td>
                        <td>{{ $penjualanhd->customernama }}</td>
                        <td>{{ $penjualanhd->customeralamat }}</td>
                        <td>{{ $penjualanhd->customernotelp }}</td>
                        <td>{{ $penjualanhd->namasales }}</td>
                        <td>{{ $penjualanhd->namalock }}</td>
                        <td>{{ $penjualanhd->namadriver }}</td>
                        <td>{{ $penjualanhd->pjkolektornota }}</td>
                        <td>{{ $penjualanhd->pjadminnota }}</td>
                        <td class="rata-kanan">{{ number_format(($penjualanhd->hargajual_total ?? 0), 0, ',', '.') }}</td>
                        <td>
                            <select readonly>
                                <option>Lihat barang</option>
                                @foreach($penjualanhd->joinPenjualandt as $penjualandt)
                                <optgroup label="{{ $penjualandt->joinTimSetupPaket->nama }} (Qty: {{ $penjualandt->jumlah + $penjualandt->jumlahkoreksi }})">
                                    @foreach($penjualandt->joinTimSetupPaket->joinTimSetupBarang as $barang)
                                    <option disabled>{{ $barang->joinBarang->nama }}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </td>
                        <td><a href="{{ asset('storage/' . $penjualanhd->fotoktp ) }}" target="_blank">{{ $penjualanhd->fotoktp }}</a></td>
                        <td><a href="{{ asset('storage/' . $penjualanhd->fotonota ) }}" target="_blank">{{ $penjualanhd->fotonota }}</a></td>
                        <td><a href="{{ asset('storage/' . $penjualanhd->fotonotarekap ) }}" target="_blank">{{ $penjualanhd->fotonotarekap }}</a></td>
                        <td>{{ $penjualanhd->status }}</td>
                        <td>{{ $penjualanhd->joinUser->name }}</td>
                        <td>{{ $penjualanhd->updated_at }}</td>
                        <td>{{ $penjualanhd->joinUserLock->name ?? '' }}</td>
                        <td>{{ $penjualanhd->validatedlock_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        @if ((auth()->user()->roles ?? '')== 'SUPERVISOR')
                        <td></td>
                        @endif
                        <td></td>
                        <td>#{{ $penjualanhds->count() }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="rata-kanan">Grand Total</td>
                        <td class="rata-kanan">{{ number_format(($grandTotal->totaljual ?? 0), 0, ',', '.') }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

        </div>
        {{ $penjualanhds->links() }}
    </div>

    @if ((auth()->user()->roles ?? '')== 'SUPERVISOR')
    <div class="container">
        <div class="col-md-4 col-12 mb-1 p-1 g-0">
            <div class="input-group">
                <span class="input-group-text">Download Excel</span>
                <select wire:model="exportmode" class="form-control" aria-label="Download">
                    <option value='penjualan'>Penjualan</option>
                    <option value="penjualanrekap">Penjualan Rekap</option>
                </select>
                <button wire:click="exportExcel" wire:loading.attr="disabled" class="badge bg-success bg-sm d-flex justify-content-center align-items-center custom-hover" type="button"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-spreadsheet" viewBox="0 0 16 16">
                        <path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2M9.5 3A1.5 1.5 0 0 0 11 4.5h2V9H3V2a1 1 0 0 1 1-1h5.5zM3 12v-2h2v2zm0 1h2v2H4a1 1 0 0 1-1-1zm3 2v-2h3v2zm4 0v-2h3v1a1 1 0 0 1-1 1zm3-3h-3v-2h3zm-7 0v-2h3v2z" />
                    </svg></button>
            </div>
        </div>
    </div>
    @endif

    <!-- modal upload spreadsheet -->
    <div wire:ignore.self class="modal fade" id="ModalUploadSheet" tabindex="-1" aria-labelledby="ModalUploadLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalUploadLabel">Upload Data</h1>
                    <button wire:click="cancleUploadToSpreadsheet" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    upload spreadsheet nota {{ $nota }}?
                </div>
                <div class="modal-footer">
                    <button wire:click="cancleUploadToSpreadsheet" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button wire:click="uploadToSpreadsheet()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <!-- <script>
        function toggleDropdown(element) {
            element.parentElement.classList.toggle('show');
        }
    </script> -->
</div>

</div>