<div>
    {{-- Close your eyes. Count to one. That is how long forever feels. --}}
    <link href="{{ asset('css/styles_table_res.css') }}" rel="stylesheet" />
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
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}
    <link href="{{ asset('css/styleSelect2.css') }}" rel="stylesheet" />
    <div class="container">
        <h2 class="text-center">{{ $title }}</h2>

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
        </div>

        <div class="col-12 mt-1 mb-1">
            <input class="border rounded" wire:model.live.debounce.500ms="cari" type="text" id="cari" placeholder="cari nota/nama ....">
        </div>

        <div class="table-responsive mb-1">
            <table class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Tim</th>
                        <th>Tgl Retur</th>
                        <th>No. Retur</th>
                        <th>Nota</th>
                        <th>Nama Customer</th>
                        <th class="rata-kanan">Total Retur</th>
                        <th>Act</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penjualanreturs as $penjualanretur)
                    <tr>
                        <td>{{ $penjualanretur->tim }}</td>
                        <td>{{ $penjualanretur->tglretur }}</td>
                        <td>{{ $penjualanretur->noretur }}</td>
                        <td>{{ $penjualanretur->nota }}</td>
                        <td>{{ $penjualanretur->customernama }}</td>
                        <td class="rata-kanan">{{ number_format(($penjualanretur->totalretur ?? 0), 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td>#{{ $penjualanreturs->count() }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="rata-kanan"></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>

        </div>
        {{ $penjualanreturs->links() }}
    </div>
</div>