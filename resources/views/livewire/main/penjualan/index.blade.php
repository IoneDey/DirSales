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

        <a wire:click="entryNew" type="button" class="btn btn-primary" href="#entry">Baru</a>

        <table class="table table-sm table-hover table-striped table-bordered border-primary-subtle mt-1">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tim</th>
                    <th>Nota</th>
                    <th>Nama Customer</th>
                    <th>Alamat Customer</th>
                    <th>No. Telepon Customer</th>
                    <th class="rata-kanan">Total Jual</th>
                    <th>User Entry</th>
                    <th>Updated At</th>
                    <th>Act</th>
                </tr>
            </thead>
            <tbody>
                @foreach($listPenjualans as $detail)
                <tr>
                    <td>{{ $detail->tgljual }}</td>
                    <td>{{ $detail->Tim }}</td>
                    <td>{{ $detail->nota }}</td>
                    <td>{{ $detail->customernama }}</td>
                    <td>{{ $detail->customeralamat }}</td>
                    <td>{{ $detail->customernotelp }}</td>
                    <td class="rata-kanan">{{ number_format(($detail->totaljual ?? 0), 0, ',', '.') }}</td>
                    <td>{{ $detail->userentry }}</td>
                    <td>{{ $detail->updated_at }}</td>
                    <td>
                        <a wire:click="edit({{ $detail->id }})" wire:loading.attr="disabled" title="Edit" type="button" class="badge bg-warning bg-sm" href="#entry"><i class="bi bi-pencil-fill"></i></a>
                        <a wire:click="confirmDelete({{ $detail->id }})" wire:loading.attr="disabled" title="Delete" type="button" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#ModalDelete"><i class="bi bi-eraser"></i></a>
                        <a wire:click="confirmValid({{ $detail->id }},{{ $detail->totaljual ?? 0 }})" wire:loading.attr="disabled" title="Valid" type="button" class="badge bg-success bg-sm" data-bs-toggle="modal" data-bs-target="#ModalValid"><i class=" bi bi-clipboard-check"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- modal delete -->
        <div wire:ignore.self class="modal fade" id="ModalDelete" tabindex="-1" aria-labelledby="ModalDeleteLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="ModalDeleteLabel">Hapus Data</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Anda yakin hapus data Nota: {{ $nota }}?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button wire:click="delete()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                    </div>
                </div>
            </div>
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
                        @if ($jumlahTotal===0)
                        Tidak bisa validasi, jumlah total penjualan = 0
                        @else
                        Anda yakin validasi Nota: {{ $nota }}?
                        @endif
                    </div>
                    <div class="modal-footer">
                        @if ($jumlahTotal===0)
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Close</button>
                        @else
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button wire:click="valid()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div id="entry" class="custom-divider mt-2 mb-3"></div>

        @if ($isEditor)
        @include('livewire.main.penjualan.entry')
        @endif


    </div>