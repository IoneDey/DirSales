<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <link href="{{ asset('css/styles_table_res.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styleSelect2.css') }}" rel="stylesheet" />
    <style>
        /* untuk tabel scroll */
        table th,
        table td {
            white-space: nowrap;
        }

        .table-responsive {
            max-height: 90vh;
            overflow-y: auto;
            overflow-x: auto;
        }

        @media (max-height: 800px) {
            .table-responsive {
                max-height: 66vh;
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
    <div class="container">
        <h2 class="text-center">{{ $title }}</h2>

        <div class="row justify-content-center">
            <div class="col-md-3 col-12 mb-1 p-1 g-0">
                <div class="d-flex align-items-left" x-data="{ isUpdate: @entangle('isUpdate') }" wire:ignore>
                    <span class="me-0 input-group-text" style="padding: 0.375rem 0.5rem; border-radius: 0.25rem 0 0 0; margin-right: -0.5rem; height: 38px;">Tim</span>
                    <select x-data="{item: @entangle('tim')}" x-init="$($refs.select2ref).select2(); $($refs.select2ref).on('change', function(){$wire.set('tim', $(this).val());});" x-effect="$refs.select2ref.value = item; $($refs.select2ref).select2();" x-ref="select2ref" :disabled="isUpdate" class="form-select" aria-label="Tim">
                        <option value='Semua'>Semua</option>
                        @foreach ($dbTimsetups as $dbTimsetup)
                        <option value="{{ $dbTimsetup->joinTim->nama }}">{{ $dbTimsetup->joinTim->nama }}</option>
                        @endforeach
                    </select>
                </div>
                @error('timsetupid')
                <span style="font-size: smaller; color: red;">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="table-responsive mb-1">
            @foreach ($salesData as $tim => $sales)
            <h5>{{ $tim }}</h5>
            <table class="table table-sm table-bordered table-striped table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Nota</th>
                        <th>Nama Pelanggan</th>
                        <th>Total Penjualan</th>
                        <th>Total Penagihan</th>
                        <th>Sisa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                    <tr>
                        <td>{{ $sale->Nota }}</td>
                        <td>{{ $sale->Customernama }}</td>
                        <td class="rata-kanan">{{ number_format(($sale->TotalPenjualan ?? 0), 0, ',', '.') }}</td>
                        <td class="rata-kanan">{{ number_format(($sale->TotalPenagihan ?? 0), 0, ',', '.') }}</td>
                        <td class="rata-kanan">{{ number_format(($sale->Sisa ?? 0), 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endforeach
        </div>
    </div>
</div>