<div>
    {{-- The Master doesn't talk, he acts. --}}
    <script src="{{ asset('js/formatAngka.js') }}"></script>
    @livewireStyles
    <link href="{{ asset('css/style_alert_center_close.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styles_table_res.css') }}" rel="stylesheet" />
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

        .result-table thead th,
        .result-table tbody td {
            padding: 1px;
            text-align: left;
        }

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
            width: 100%;
            box-sizing: border-box;
            /* ensures the width includes padding and border */
        }

        .text-right {
            text-align: right;
        }

        table th,
        table td {
            white-space: nowrap;
        }

        .table-responsive {
            /* max-height: 400px; */
            /* Sesuaikan tinggi sesuai kebutuhan */
            overflow-y: auto;
            overflow-x: auto;
        }

        .info-box {
            border: 1px solid #ddd;
            /* Border */
            padding: 10px;
            /* Padding */
            margin: 5px 0;
            /* Margin */
            border-radius: 5px;
            /* Rounded corners */
            background-color: #f9f9f9;
            /* Background color */
            font-size: 0.775em;
            /* Smaller font size */
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
    <div class="container">
        <div class="row">
            <div class="col-7">
                <div class="input-group">
                    <div class="col-12" style="padding: 1px; position: relative;">
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
                                    <tr wire:click="selectNota('{{ $result->timsetupid }}','{{ $result->nota }}')" style="cursor: pointer;">
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
                        <span class="input-label">Tanggal Penagihan/Pengambilan</span>
                        <input wire:model.live="tglpenagihan" type="date" class="form-control">
                        @error('tglpenagihan')
                        <span style="font-size: smaller; color: red;">{{ $message }}</span>
                        @enderror

                        @if ($dbInfoSPK)
                        <div class="info-box">
                            <div>F.Y.I:</div>
                            <div>Angsuran ke: {{ $dbInfoSPK->angsuranke }}</div>
                            <div>Nilai Angsuran: {{ number_format(($dbInfoSPK->perangsuran ?? 0), 0, ',', '.') }}</div>
                            <div>Penagihan A{{ $dbInfoSPK->angsuranke }}: {{ number_format(($dbInfoSPK->jmlpenagihan ?? 0), 0, ',', '.') }}</div>
                        </div>
                        @endif
                    </div>

                    <div class="input-group-item">
                        <span class="input-label">Yang Menagih</span>
                        <select wire:model="namapenagih" type="text" class="form-select">
                            <option value=""></option>
                            @if($dbKolektors)
                            @foreach ($dbKolektors as $dbKolektor)
                            <option value="{{ $dbKolektor->nama }}">{{ $dbKolektor->nama }}</option>
                            @endforeach
                            @endif
                        </select>
                        @error('namapenagih')
                        <span style="font-size: smaller; color: red;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-group-item" x-data="{ Jumlahbayar: @entangle('jumlahbayar') }">
                        <span class="input-label">Jumlah Pembayaran
                        </span>
                        <input wire:model.live="jumlahbayar" type="text" inputmode="text" class="form-control text-right" x-model="jumlahbayar" x-on:input="formatAngka($event)">
                        @error('jumlahbayar')
                        <span style="font-size: smaller; color: red;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-group-item" x-data="{ Biayaadmin: @entangle('biayaadmin')}">
                        <span class="input-label">Biaya Admin/ongkos koordinator (jika Transfer)</span>
                        <input wire:model.live="biayaadmin" type="text" inputmode="text" class="form-control text-right" x-model="Biayaadmin" x-on:input="formatAngka($event)">
                        @error('biayaadmin')
                        <span style="font-size: smaller; color: red;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-group-item" x-data="{ Biayakomisi: @entangle('biayakomisi')}">
                        <span class="input-label">Potongan Komisi Penagihan Warga</span>
                        <input wire:model.live="biayakomisi" type="text" inputmode="text" class="form-control text-right" x-model="Biayakomisi" x-bind:disabled="isDisabled" x-on:input="formatAngka($event)" {{ ($angsuranperiode ?? 0) == ($dbInfoSPK->angsuranke ?? 0) ? '' : 'disabled' }}>
                        @error('biayakomisi')
                        <span style="font-size: smaller; color: red;">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="input-group-item" x-data="{ Jumlah: @entangle('jumlah') }">
                        <span class="input-label">Jumlah Total</span>
                        <input wire:model="jumlah" type="text" inputmode="text" class="form-control text-right" x-model="Jumlah" x-on:input="formatAngka($event)" disabled>
                        @error('jumlah')
                        <span style="font-size: smaller; color: red;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-group-item mb-0">
                        <span class="input-label" for="inputKwitans">Foto Kwitansi</span>
                        <input wire:model="fotokwitansi" accept="image/png, image/jpeg" type="file" class="form-control" id="inputKwitans">
                        <div wire:loading wire:target="fotokwitansi">Uploading...</div>
                        @error('fotokwitansi')
                        <span style="font-size: smaller; color: red;">{{ $message }}</span>
                        @enderror
                        @if (is_string($fotokwitansi) && strlen($fotokwitansi) > 0)
                        <img src="{{ asset('storage/' . $fotokwitansi) }}" class="img-fluid rounded mx-auto d-block mt-2" style="transform: rotate({{ $rotation }}deg);" alt="...">
                        @else
                        @if ($fotokwitansi)
                        <img src="{{ $fotokwitansi->temporaryUrl() }}" class="img-fluid rounded mx-auto d-block mt-2" style="transform: rotate({{ $rotation }}deg);" alt="...">
                        @endif
                        @endif
                        @if ($fotokwitansi)
                        <button wire:click="rotate">Rotate</button>
                        @endif
                    </div>
                </div>
                <div>
                    <button wire:click="create" type="button" class="btn btn-primary mt-1">Simpan</button>
                    <button wire:click="clear" type="button" class="btn btn-secondary mt-1">Bersihkan</button>
                </div>
            </div>

            <div class="col-5">
                <!-- kartu piutang -->
                <div class="table-responsive input-group">
                    <div class="input-group-item">
                        <div>Kartu Piutang Nota</div>
                    </div>
                    <table class="table table-sm table-hover table-striped table-bordered border-primary-subtle mt-1" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nota</th>
                                <th class="rata-kanan">Debet</th>
                                <th class="rata-kanan">Kredit</th>
                                <th class="rata-kanan">Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dbKartus as $item)
                            <tr>
                                <td>{{ $item->tgljual }}</td>
                                <td>{{ $item->nota }}</td>
                                <td class="rata-kanan">{{ number_format(($item->debet ?? 0), 0, ',', '.') }}</td>
                                <td class="rata-kanan">{{ number_format(($item->kredit ?? 0), 0, ',', '.') }}</td>
                                <td class="rata-kanan">{{ number_format(($item->saldo ?? 0), 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="custom-divider mt-2 mb-3"></div>

                <!-- info angsuran -->
                <div class="table-responsive input-group">
                    <!-- <div class="input-group-item form-check-inline">
                        <span class="input-label">Metode Reschedule Angsuran</span>
                        <div class="input-group">
                            <div class="form-check">
                                <input value="Avg" wire:model.live="selectedOption" class="form-check-input" type="radio" name="opt1" id="opt1">
                                <label class="form-check-label" for="opt1">
                                    Avg
                                </label>
                            </div>
                            <div class="form-check">
                                <input value="Up" wire:model.live="selectedOption" class="form-check-input" type="radio" name="opt2" id="opt2">
                                <label class="form-check-label" for="opt2">
                                    Up
                                </label>
                            </div>
                            <div class="form-check">
                                <input value="Down" wire:model.live="selectedOption" class="form-check-input" type="radio" name="opt3" id="opt3">
                                <label class="form-check-label" for="opt3">
                                    Down
                                </label>
                            </div>
                        </div>
                    </div> -->

                    <div class="input-group-item">
                        <div>Informasi Angsuran</div>
                        <div>Tgl Penjualan: {{ $tgljual }} - Rp. {{ number_format(($jmljual ?? 0), 0, ',', '.') }}</div>
                        <div>Angsuran Hari: {{ $angsuranhari }} - Angsuran Periode: {{ $angsuranperiode }}</div>
                    </div>
                    <table class="table table-sm table-hover table-striped table-bordered border-primary-subtle mt-1" style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Ke</th>
                                <th>Tgl Angsuran</th>
                                <!-- <th class="rata-kanan">Angsuran</th> -->
                                <th>Tgl Penagihan</th>
                                <th class="rata-kanan">Jml Penagihan</th>
                                <th>Nama Penagih</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($dbInfoAngsuran as $item)
                            <tr>
                                <td>{{ $item->angsuranke }}</td>
                                <td>{{ $item->tglangsuran }}</td>
                                <!-- <td class="rata-kanan">{{ number_format(($item->perangsuran ?? 0), 0, ',', '.') }}</td> -->
                                <td>{{ $item->tglpenagihan }}</td>
                                <td class="rata-kanan">{{ number_format(($item->jmlpenagihan ?? 0), 0, ',', '.') }}</td>
                                <td>{{ $item->namapenagih }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>