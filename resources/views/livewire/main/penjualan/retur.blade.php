<div>
    <script src="{{ asset('js/formatAngka.js') }}"></script>
    {{-- To attain knowledge, add things every day; To attain wisdom, subtract things every day. --}}
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

        .full-width-input {
            width: 100%;
            box-sizing: border-box;
            /* ensures padding and border are included in the element's total width */
        }

        /* Additional styles for table */
        th,
        td {
            padding: 8px;
            /* Adjust padding as needed */
            text-align: left;
            /* Align text to the left */
        }

        /* untuk cari nota  */
        #search-input {
            display: block;
            width: 100%;
            box-sizing: border-box;
            /* ensures the width includes padding and border */
        }

        .search-results {
            position: absolute;
            top: 100%;
            /* Menempatkan hasil pencarian tepat di bawah input */
            left: 0;
            background: lightgray;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 255, 255, 0.5);
            z-index: 1000;
            max-height: 300px;
            overflow-y: auto;
            overflow-x: auto;
            width: 100%;
            box-sizing: border-box;
            /* ensures the width includes padding and border */
        }

        .result-table th,
        .result-table td {
            white-space: nowrap;
            /* Mencegah pembungkusan teks */
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

        <div class="col-6" style="padding: 1px; position: relative;">
            <div class="input-group-item" style="position: relative;">
                <span class="input-label">Nota/Customer</span>
                <input wire:model.live="nota" type="text" class="form-control" id="search-input" placeholder="cari berdasakan nota / nama customer">
                @error('nota')
                <span style="font-size: smaller; color: red;">{{ $message }}</span>
                @enderror
            </div>
            @if(!empty($results) && !$isNota)
            <div id="search-results" class="search-results">
                <table class="result-table table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>Nota</th>
                            <th>Nama Customer</th>
                            <th>Alamat Customer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($results as $result)
                        <tr wire:click="selectNota('{{ $result->nota }}')" style="cursor: pointer;">
                            <td>{{ $result->nota }}</td>
                            <td>{{ $result->customernama }}</td>
                            <td>{{ $result->customeralamat }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <div class="col-12" style="padding: 1px;">
            <div class="input-group">
                <div class="input-group-item">
                    <span class="input-label">Tanggal Penjualan</span>
                    <input wire:model="tgljual" type="date" class="form-control" disabled>
                </div>
                <div class="input-group-item">
                    <span class="input-label">Tim</span>
                    <input wire:model="tim" type="text" class="form-control" disabled>
                </div>
                <div class="input-group-item">
                    <span class="input-label">PT</span>
                    <input wire:model="pt" type="text" class="form-control" disabled>
                </div>
                <div class="input-group-item">
                    <span class="input-label">Kota</span>
                    <input wire:model="kota" type="text" class="form-control" disabled>
                </div>
                <div class="input-group-item">
                    <span class="input-label">Nama Customer</span>
                    <input wire:model="customernama" type="text" class="form-control" disabled>
                </div>
                <div class="input-group-item">
                    <span class="input-label">Alamat Customer</span>
                    <input wire:model="customeralamat" type="text" class="form-control" disabled>
                </div>
                <div class="input-group-item">
                    <span class="input-label">Total Penjualan: {{ number_format(($totaljual ?? 0), 0, ',', '.') }}</span>
                    <input wire:model="totaljualRp" type="text" class="form-control" disabled>
                </div>
            </div>
        </div>
    </div>

    <div class="custom-divider mt-2 mb-3"></div>
    <div class="container">
        <label>Retur Barang</label>
        <div class="input-group">
            <div class="input-group p-0 g-0 m-0">
                <div class="input-group-item">
                    <span class="input-label" id="tglretur">Tgl Retur</span>
                    <input wire:model="tglretur" type="date" class="form-control" disabled>
                </div>
                <div class="input-group-item">
                    <span class="input-label">Barang</span>
                    <input wire:model="namabarang" type="text" class="form-control" disabled>
                </div>
                <div class="input-group-item">
                    <span class="input-label">Jumlah Max Retur</span>
                    <input wire:model="maxretur" type="text" class="form-control" disabled>
                </div>
            </div>
            <div class="input-group p-0 g-0 m-0">
                <div class="input-group-item">
                    <span class="input-label">Qty Retur</span>
                    <input wire:model.live="qty" type="text" class="form-control">
                    @error('qty')
                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-group-item" x-data="{ Harga: @entangle('harga') }">
                    <span class="input-label">@Harga</span>
                    <input wire:model.live="harga" type="text" inputmode="text" class="form-control text-right" x-model="Harga" x-on:input="formatAngka($event)">
                    @error('harga')
                    <span style="font-size: smaller; color: red;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="input-group-item">
                    <span class="input-label">Total Retur</span>
                    <input wire:model="totalretur" type="text" class="form-control" disabled>
                </div>
            </div>
            <div>
                <button wire:click="simpan" type="button" class="btn btn-primary">Simpan</button>
            </div>
        </div>

        <label>Data Detail Barang Terjual</label>
        <table class="table table-sm table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>Nama Paket</th>
                    <th>Nama Barang</th>
                    <th>Qty Barang</th>
                    <th>Qty Retur</th>
                    <th>Total Retur</th>
                    <th>Qty Sisa</th>
                    <th>Act</th>
                </tr>
            </thead>
            <tbody>
                @if ($dbDetailJuals)
                @foreach($dbDetailJuals as $dbDetailJual)
                <tr>
                    <td>{{ $dbDetailJual->namapaket }} (<label>@</label>{{ number_format(($dbDetailJual->hargajual ?? 0), 0, ',', '.') }})</td>
                    <td>{{ $dbDetailJual->namabarang }}</td>
                    <td>{{ $dbDetailJual->jmljual }}</td>
                    <td>{{ $dbDetailJual->qtyret }}</td>
                    <td>{{ $dbDetailJual->totalret }}</td>
                    <td>{{ $dbDetailJual->maxretur }}</td>
                    <td>
                        <a wire:click="getDataRetur({{ $dbDetailJual->timsetuppaketid }},{{ $dbDetailJual->barangid }})" type="button" title="Retur" href="#tglretur" class="badge bg-warning bg-sm"><i class="bi bi-arrow-clockwise"></i></a>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>