<div>
    <link href="{{ asset('css/style_alert_center_close.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styles_table_res.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/tabelsort.css') }}" rel="stylesheet" />

    <style>
        .form-floating .form-control {
            border-width: 1px;
            border-color: blue;
            border-radius: 5px;
            border-style: solid;
        }

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
                        <div class="col-12 g-1">
                            <div class="form-floating mb-1">
                                <input wire:model="nama" type="text" class="form-control" id="nama" placeholder="">
                                <label for="nama">Nama</label>
                            </div>
                            @error('nama')
                            <span style="font-size: smaller; color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12 g-1">
                            <div class="form-floating mb-1">
                                <input wire:model="kode" type="text" class="form-control" id="kode" placeholder="">
                                <label for="kode">Kode S.Sheet</label>
                            </div>
                            @error('kode')
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

            <div class="input-group mb-1">
                <a wire:click="exportPDF" wire:loading.attr="disabled" type="button" class="badge bg-danger bg-sm" title="Export To PDF">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5zM1.6 11.85H0v3.999h.791v-1.342h.803q.43 0 .732-.173.305-.175.463-.474a1.4 1.4 0 0 0 .161-.677q0-.375-.158-.677a1.2 1.2 0 0 0-.46-.477q-.3-.18-.732-.179m.545 1.333a.8.8 0 0 1-.085.38.57.57 0 0 1-.238.241.8.8 0 0 1-.375.082H.788V12.48h.66q.327 0 .512.181.185.183.185.522m1.217-1.333v3.999h1.46q.602 0 .998-.237a1.45 1.45 0 0 0 .595-.689q.196-.45.196-1.084 0-.63-.196-1.075a1.43 1.43 0 0 0-.589-.68q-.396-.234-1.005-.234zm.791.645h.563q.371 0 .609.152a.9.9 0 0 1 .354.454q.118.302.118.753a2.3 2.3 0 0 1-.068.592 1.1 1.1 0 0 1-.196.422.8.8 0 0 1-.334.252 1.3 1.3 0 0 1-.483.082h-.563zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638z" />
                    </svg>
                </a>
            </div>

            <div class="col-12 mb-1">
                <input class="border rounded" wire:model.live.debounce.500ms="cari" type="text" id="cari" placeholder="cari....">
            </div>

            <div>

                <table class="table table-sm table-hover table-striped table-bordered border-primary-subtle table-sortable">
                    <thead>
                        <th>#</th>
                        <th class="sort @if ($sortColumn== 'nama') {{ $sortDirection }} @endif" wire:click="sort('nama')">Nama</th>
                        <th class="sort @if ($sortColumn== 'kode') {{ $sortDirection }} @endif" wire:click="sort('kode')">Kode S.Sheet</th>
                        <th>Act</th>
                    </thead>
                    <tbody>
                        @foreach ($datas as $dbdata)
                        <tr>
                            <td>{{ (($datas->currentPage()-1)*$datas->perPage()) + $loop->iteration }}</td>
                            <td>{{ $dbdata->nama }}</td>
                            <td>{{ $dbdata->kode }}</td>
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