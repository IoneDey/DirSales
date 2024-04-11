<div>
    <div>
        <form class='p-2'>
            <div class="row">
                <div class="col-md-2 position-relative d-flex align-items-end">
                    <!-- del-->
                </div>
                <div class="col-md-3 offset-md-7">
                    <div class="form-floating">
                        <input wire:model.live="textcari" style="width: 100%;" type="text" name="search" id="floatingInputcari" placeholder=" " class="form-control mb-2">
                        <label for="floatingInputcari"> Cari</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    {{ $dataKota    ->links() }}
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
</div>