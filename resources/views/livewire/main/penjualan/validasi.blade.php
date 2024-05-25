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
                <pre>{!! session('ok') !!} </pre>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label=""></button>
        </div>
        @endif

        @if(session()->has('error'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <ul>
                <pre>{!! session('error') !!} </pre>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label=""></button>
        </div>
        @endif

        <h2 class="text-center">{{ $title }}</h2>

        <div class="container">
            <div style="overflow-x: 100vh;">
                <table class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Tim</th>
                            <th>Nota</th>
                            <th>Kota</th>
                            <th>Kecamatan</th>
                            <th>Tgl Jual</th>
                            <th>Nama Customer</th>
                            <th>Alamat Customer</th>
                            <th>Telp Customer</th>
                            <th>Share Loc</th>
                            <th>Nama Sales</th>
                            <th>Nama Lock</th>
                            <th>Nama Driver</th>
                            <th>PJ Kolektor Nota</th>
                            <th>PJ Admin Nota</th>
                            <th class="rata-kanan">Tot Jumlah</th>
                            <th>Barang</th>
                            <th>Foto KTP</th>
                            <th>Foto Nota</th>
                            <th>Foto Nota Rekap</th>
                            <th>Status</th>
                            <th>User Entry</th>
                            <th>Timestamp</th>
                            <th>Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($penjualanhds as $penjualanhd)
                        <tr>
                            <td>{{ $penjualanhd->joinTimSetup->jointim->nama }}</td>
                            <td>{{ $penjualanhd->joinTimSetup->joinkota->nama }}</td>
                            <td>{{ $penjualanhd->kecamatan }}</td>
                            <td>{{ $penjualanhd->nota }}</td>
                            <td>{{ $penjualanhd->tgljual }}</td>
                            <td>{{ $penjualanhd->customernama }}</td>
                            <td>{{ $penjualanhd->customeralamat }}</td>
                            <td>{{ $penjualanhd->customernotelp }}</td>
                            <td>{{ $penjualanhd->shareloc }}</td>
                            <td>{{ $penjualanhd->namasales }}</td>
                            <td>{{ $penjualanhd->namalock }}</td>
                            <td>{{ $penjualanhd->namadriver }}</td>
                            <td>{{ $penjualanhd->pjkolektornota }}</td>
                            <td>{{ $penjualanhd->pjadminnota }}</td>
                            <td class="rata-kanan">{{ number_format(($penjualanhd->hargajual_total ?? 0), 0, ',', '.') }}</td>
                            <td>
                                @foreach($penjualanhd->joinPenjualandt as $penjualandt)
                                <li>
                                    {{ $penjualandt->joinTimSetupPaket->nama }} (Qty: {{ $penjualandt->jumlah+$penjualandt->jumlahkoreksi }})
                                    <ul>
                                        @foreach($penjualandt->joinTimSetupPaket->joinTimSetupBarang as $barang)
                                        <li>{{ $barang->joinBarang->nama }}</li>
                                        @endforeach
                                    </ul>
                                </li>
                                @endforeach
                            </td>
                            <td><a href="{{ asset('storage/' . $penjualanhd->fotoktp ) }}" target="_blank">{{ $penjualanhd->fotoktp }}</a></td>
                            <td><a href="{{ asset('storage/' . $penjualanhd->fotonota ) }}" target="_blank">{{ $penjualanhd->fotonota }}</a></td>
                            <td><a href="{{ asset('storage/' . $penjualanhd->fotonotarekap ) }}" target="_blank">{{ $penjualanhd->fotonotarekap }}</a></td>
                            <td>{{ $penjualanhd->status }}</td>
                            <td>{{ $penjualanhd->joinUser->name }}</td>
                            <td>{{ $penjualanhd->updated_at }}</td>
                            <td>
                                <a type="button" class="badge bg-warning bg-sm" href="{{ route('penjualanvalidasiedit', ['id' => $penjualanhd->id]) }}"><i class="bi bi-pencil-fill"></i></a>
                                <a wire:click="cekValidasi('{{ $penjualanhd->nota }}')" type="button" class="badge bg-success bg-sm" data-bs-toggle="modal" data-bs-target="#ModalValid"><i class="bi bi-lock"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
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
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- test wa dan spreadsheet -->
        <!-- <button wire:click="testSendWA"> test send wa</button> -->
    </div>

    <!-- modal valid -->
    <div wire:ignore.self class="modal fade" id="ModalValid" tabindex="-1" aria-labelledby="ModalValidLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="ModalValidLabel">Validasi</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($validMessage != '')
                    {!! $validMessage !!}
                    @else
                    Anda yakin validasi Nota: {{ $nota }}?
                    @endif
                </div>
                <div class="modal-footer">
                    @if ($validMessage != '')
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                    @else
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button wire:click="valid()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var myModal = document.getElementById('ModalValid');
            myModal.addEventListener('show.bs.modal', function() {
                // Clear modal content before showing
                var modalBody = myModal.querySelector('.modal-body');
                modalBody.innerHTML = ''; // Clearing modal body
                var modalFooter = myModal.querySelector('.modal-footer');
                modalFooter.innerHTML = ''; // Clearing modal body
            });
        });
    </script>
</div>