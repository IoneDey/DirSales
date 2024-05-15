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
    <h2 class="text-center">{{ $title }}</h2>
    <div class="container">

        <div class="col-3">
            <div class="input-group">
                <span class="input-group-text mb-2">Tim</span>
                <select wire:model.live="timid" type="text" class="form-control mb-2">
                    <option value=""></option>
                    @foreach ($dbTims as $dbtTim)
                    <option value="{{ $dbtTim->id }}">{{ $dbtTim->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <table class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Tim</th>
                    <th>Nota</th>
                    <th>Customer Nama</th>
                    <th class="rata-kanan">Debet</th>
                    <th class="rata-kanan">Kredit</th>
                    <th class="rata-kanan">Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dbKartuPiutang as $data)
                <tr>
                    <td>{{ $data->tgljual }}</td>
                    <td>{{ $data->tim }}</td>
                    <td>{{ $data->nota }}</td>
                    <td>{{ $data->customernama }}</td>
                    <td class="rata-kanan">{{ number_format(($data->debet ?? 0), 0, ',', '.') }}</td>
                    <td class="rata-kanan">{{ number_format(($data->kredit ?? 0), 0, ',', '.') }}</td>
                    <td class="rata-kanan">{{ number_format(($data->Saldo ?? 0), 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>