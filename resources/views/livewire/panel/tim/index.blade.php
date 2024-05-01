<div>
    <link href="{{ asset('css/style_alert_center_close.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styles_table_res.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/tabelsort.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styleSelect2.css') }}" rel="stylesheet" />

    <style>
        .custom-divider {
            height: 1px;
            background-color: blue;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 1);
            margin: 20px 0;
        }
    </style>

    <div id="top"></div>
    <h2 class="text-center">{{ $title }}</h2>

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

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <form wire:submit="create" action="">
                    <div class="row">
                        <div class="col-6 g-1">
                            <div class="mb-1">
                                <span class="input-label">Nama</span>
                                <input class="form-control" wire:model="nama" type="text" id="" placeholder="" name="iname">
                            </div>
                            @error('nama')
                            <span style="font-size: smaller; color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-6 g-1">
                            <div class="select2-containe mb-1" wire:ignore>
                                <span for="input-label">PT</span>
                                <select x-data="{
                                        item: @entangle('ptid')
                                        }" x-init="$($refs.select2ref).select2();
                                            $($refs.select2ref).on('change', function(){$wire.set('ptid', $(this).val());
                                        });" x-effect="
                                            $refs.select2ref.value = item;
                                            $($refs.select2ref).select2();" x-ref="select2ref">
                                    <option value=""></option>
                                    @foreach ($dbPts as $dbPt)
                                    <option value={{ $dbPt->id }}>{{ $dbPt->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('ptid')
                            <span style="font-size: smaller; color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    @if ($isUpdate)
                    <button wire:click="update" wire:loading.attr="disabled" type="button" id="update" class="btn btn-primary">Update</button>
                    @else
                    <button wire:click="create" wire:loading.attr="disabled" type="button" id="simpan" class="btn btn-primary">Simpan</button>
                    @endif
                    <button wire:click="clear" wire:loading.attr="disabled" type="button" id="bersihkan" class="btn btn-secondary">Bersihkan</button>
                </form>
            </div>

            <div class="custom-divider mt-2 mb-3"></div>

            <div class="col-12 mb-1">
                <input class="border rounded" wire:model.live.debounce.500ms="cari" type="text" id="cari" placeholder="cari....">
            </div>

            <div>
                <table class="table table-sm table-hover table-striped table-bordered border-primary-subtle table-sortable">
                    <thead>
                        <th>#</th>
                        <th class="sort @if ($sortColumn=='nama') {{ $sortDirection }} @endif" wire:click="sort('nama')">Nama</th>
                        <th class="sort @if ($sortColumn=='ptid') {{ $sortDirection }} @endif" wire:click="sort('ptid')">PT</th>
                        <th>Act</th>
                    </thead>
                    <tbody>
                        @foreach ($datas as $dbdata)
                        <tr>
                            <td>{{ (($datas->currentPage()-1)*$datas->perPage()) + $loop->iteration }}</td>
                            <td>{{ $dbdata->nama }}</td>
                            <td>{{ $dbdata->joinPt->nama }}</td>
                            <td>
                                <a wire:click="edit({{ $dbdata->id }})" wire:loading.attr="disabled" type="button" class="badge bg-warning bg-sm" href="#top"><i class=" bi bi-pencil-fill"></i></a>
                                <a wire:click="confirmDelete({{ $dbdata->id }})" wire:loading.attr="disabled" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#ModalDelete"><i class="bi bi-eraser"></i></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $datas->links() }}
            </div>
        </div>


        <!-- untuk modal confirm delete -->
        <div wire:ignore.self class="modal fade" id="ModalDelete" tabindex="-1" aria-labelledby="ModalDeleteLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="ModalDeleteLabel">Hapus Data</h1>
                        <button wire:click="clear" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Anda yakin hapus data {{ $nama }}?
                    </div>
                    <div class="modal-footer">
                        <button wire:click="clear" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button wire:click="delete()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>