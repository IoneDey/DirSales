<div>
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
        <h2 class="text-center">{{ $title }}</h2>
        <div style="overflow-x: auto;">
            <table class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                <thead>
                    <tr>
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
                        <th>Status</th>
                        <th>User Entry</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualanhds as $penjualanhd)
                    <tr>
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
                        <td class="rata-kanan">{{ number_format(($penjualanhd->hargajual_total ?? 0), 0, ',', '.') }} </td>
                        <td>
                            @foreach($penjualanhd->joinPenjualandt as $penjualandt)
                            <li>
                                {{ $penjualandt->joinTimSetupPaket->nama }} (Qty: {{$penjualandt->jumlah}})
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
                        <td>{{ $penjualanhd->status }}</td>
                        <td>{{ $penjualanhd->joinUser->name }}</td>
                        <td>{{ $penjualanhd->updated_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>