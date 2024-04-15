<div>
    <link href="{{ asset('css/styles_table_res.css') }}" rel="stylesheet" />
    <h2 class="text-center">{{ $title }}</h2>

    <div class='container'>
        <form class='mb-1 p-2'>
            <div class="row row-cols-auto">
                <div class="col-3 offset-9 mb-1">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text" id="inputGroup-sizing-sm">Cari</span>
                        <input wire:model.live="textcari" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 mb-1">
                    <div class="row mb-0">
                        {{ $dataKota->links() }}
                    </div>
                    <table class="table table-sm table-striped table-hover table-sortable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th class="sort @if ($sortColumn== 'provinsi') {{ $sortDirection }} @endif" wire:click="sort('provinsi')">Provinsi</th>
                                <th class="sort @if ($sortColumn== 'kota_kabupaten') {{ $sortDirection }} @endif" wire:click="sort('kota_kabupaten')">Kota/Kabupaten</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ( $dataKota as $kota=>$value )
                            <tr>
                                <td>{{ $dataKota->firstItem() + $kota }}</td>
                                <td>{{ $value->provinsi }}</td>
                                <td>{{ $value->kota_kabupaten }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>