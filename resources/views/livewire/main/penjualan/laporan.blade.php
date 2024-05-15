<div>
    <link href="{{ asset('css/styles_table_res.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/style_alert_center_close.css') }}" rel="stylesheet" />

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
            gap: 1px;
            padding: 1px;
            /* background-color: #f0f0f0; */
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

        input[type="date"] {
            width: 250px;
        }

        .input-group input[type="date"] {
            padding: 1px;
        }

        .btn-primary {
            max-width: 150px;
        }

        .custom-divider {
            height: 1px;
            background-color: blue;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 1);
            margin: 20px 0;
        }

        /* untuk nowarp dalam tabel */
        table th,
        table td {
            white-space: nowrap;
        }
    </style>

    <div class="container col-12" style="padding: 3px;">
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
            <div class="row justify-content-left" style="padding: 1px; margin-bottom: 1px;">
                <div class="col-md-3 col-12 mb-0">
                    <div class="input-group">
                        <span class="input-group-text">Tgl Awal</span>
                        <input wire:model.live.debounce.500ms="tglAwal" type="date" class="form-control" aria-label="Tgl Awal">
                    </div>
                </div>

                <div class="col-md-3 col-12 mb-0">
                    <div class="input-group">
                        <span class="input-group-text">Tgl Akhir</span>
                        <input wire:model.live.debounce.500ms="tglAkhir" type="date" class="form-control" aria-label="Tgl Akhir">
                    </div>
                </div>

                <div class="col-md-3 col-12 mb-0">
                    <div class="input-group">
                        <span class="input-group-text">Status</span>
                        <select wire:model.live.debounce.500ms="status" class="form-control" aria-label="Status">
                            <option value=1>Semua</option>
                            <option value="Entry">Entry</option>
                            <option value="Entry Valid">Entry Valid</option>
                            <option value="Lock Valid">Lock Valid</option>
                            <option value="Valid">Valid</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- <div class="row justify-content-left" style="padding: 1px; margin-bottom: 1px;">
                <div class="col-md-3 col-12 mb-2">
                    <div class="input-group">
                        <button wire:click="refresh" class="btn btn-primary w-100">Refresh</button>
                    </div>
                </div>
            </div> -->

            <div style="overflow-x: 100vh;">
                <table class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                    <thead>
                        <tr>
                            @if ((auth()->user()->roles ?? '')== 'SUPERVISOR')
                            <th>Sheet</th>
                            @endif
                            <th>Tim</th>
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
                                <button wire:click="confirmUploadToSpreadsheet('{{ $penjualanhd->nota }}')" wire:loading.attr="disabled" type="button" class="badge bg-success bg-sm" data-bs-toggle="modal" data-bs-target="#ModalUploadSheet" {{ $btnDisables ? 'disabled':'' }}><i class="bi bi-cloud-arrow-up" title="Upload data ke spreadsheet"></i></button>
                                @endif
                            </td>
                            @endif
                            <td>{{ $penjualanhd->joinTimSetup->jointim->nama }}</td>
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
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            @if ((auth()->user()->roles ?? '')== 'SUPERVISOR')
                            <td></td>
                            @endif
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
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

    </div>

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

    <script>
        function toggleDropdown(element) {
            element.parentElement.classList.toggle('show');
        }
    </script>
</div>