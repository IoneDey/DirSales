<div>
    <link href="{{ asset('css/style_alert_center_close.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/styles_table_res.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/tabelsort.css') }}" rel="stylesheet" />

    <style>
        form {
            padding: 7px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Gaya untuk menetapkan lebar span */
        .span-fixed-width {
            width: 100px;
        }

        /* Menghilangkan box-shadow dan outline pada elemen input dan select saat focus */
        input.form-control:focus,
        select.form-control:focus,
        .select2-selection--single:focus {
            box-shadow: none;
            outline: none;
        }

        .container {
            padding: 10px;
            /* Padding untuk memberi ruang di dalam container */
            border: 1px solid #ccc;
            /* Border dengan ketebalan 1px dan warna abu-abu */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Shadow dengan offset (0,2px) dan blur radius 4px */
            border-radius: 8px;
            /* Border radius 8px untuk sudut container yang membulat */
            background-color: #f8f9fa;
            /* Warna latar belakang abu-abu muda */
        }
    </style>

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

    <h2 class="text-center" id="top">{{ $title }}</h2>

    <div class="row g-2 mb-2">
        <div class="col-12">
            <div class="container">
                <div class="row g-2 mb-1">
                    <form id="form_hd" class='mb-1'>
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text span-fixed-width" id="inputGroup-sizing-sm">Tim</span>
                                    <input wire:model='nomer' type="text" class="form-control" aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>

                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6">

                                {{ $ptid  }}

                                <div class="input-group input-group-sm">
                                    <span class="input-group-text span-fixed-width" id="inputGroup-sizing-sm">PT</span>
                                    <select wire:model.change="ptid" type='text' class="form-control" id="selectpt" name="selectpt">
                                        <option value="">Pilih PT</option>
                                        @foreach($dbPT as $ptList)
                                        <option value="{{ $ptList->id }}">{{ $ptList->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text span-fixed-width" id="inputGroup-sizing-sm">Kota</span>
                                    <select wire:model='kotaid' type='text' class="form-control" id="selectkota">
                                        <option value="">Pilih Kota</option>
                                        @foreach($dbKota as $KotaList)
                                        <option value="{{ $KotaList->id }}">{{ $KotaList->kota_kabupaten }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text span-fixed-width" id="inputGroup-sizing-sm">Tgl
                                        Awal</span>
                                    <input wire:model='tglawal' type="date" class="form-control" aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text span-fixed-width" id="inputGroup-sizing-sm">Tgl
                                        Akhir</span>
                                    <input wire:model='tglakhir' value="2024-01-01" type="date" class="form-control" aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text span-fixed-width" id="inputGroup-sizing-sm">P.I.C</span>
                                    <input wire:model='pic' type="text" class="form-control" aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                        </div>
                        <div>
                            @if($isUpdate)
                            <input wire:click="update()" type="button" class="btn btn-primary" value="Update"></input>
                            @else
                            <input wire:click="store()" type="button" class="btn btn-primary" value="Simpan"></input>
                            @endif
                            <input wire:click="clear()" type="button" class="btn btn-secondary" value="Clear"></input>
                        </div>
                    </form>
                </div>

                <div class="row g-2">
                    <form id="form_dt">
                        <table class="table table-sm table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 35%;">Barang</th>
                                    <th style="width: 23%;" class="rata-kanan">Hpp</th>
                                    <th style="width: 23%;" class="rata-kanan">Harga Jual</th>
                                    <th style="width: 13%;" class="rata-kanan">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select wire:model="barangid" class="form-control select2" style="width: 100%;" id="selectBarang">
                                            <option value="">Pilih Barang</option>
                                            @foreach($dbBarang as $BarangList)
                                            <option value="{{ $BarangList->id }}">{{ $BarangList->nama }} -
                                                {{ $BarangList->kode }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input wire:model="hpp" type="number" class="form-control rata-kanan" style="width: 100%;"></td>
                                    <td><input wire:model="hargajual" type="number" class="form-control rata-kanan" style="width: 100%;"></td>
                                    <td class="rata-kanan">
                                        @if($isUpdateBarang)
                                        <button wire:click="updateBarang" type="button" class="btn btn-primary">Upd</button>
                                        @else
                                        <button wire:click="storeBarang" type="button" class="btn btn-primary">Add</button>
                                        @endif
                                        <button wire:click="clearBarang" type="button" class="btn btn-secondary">Clear</button>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                @foreach($dataBarangDetail as $index)
                                <tr>
                                    <td style="display: none;">{{ $index['barangid'] }}</td>
                                    <td>{{ $index['barang'] }}</td>
                                    <td class="rata-kanan">{{ number_format($index['hpp'], 0) }}</td>
                                    <td class="rata-kanan">{{ number_format($index['hargajual'], 0) }}</td>
                                    <td class="rata-kanan">
                                        <a wire:click="editBarang({{ $index['barangid'] }})" type="button" class="badge bg-warning bg-sm"><i class="bi bi-pencil-fill"></i></a>
                                        <a wire:click="deleteBarang_confirm({{ $index['barangid'] }})" type="button" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#ModalDeleteBarang"><i class="bi bi-eraser"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="row g-2">
        <div class="col-12">
            <div class="container">
                <div class="row g-2 mb-1">
                    <form id="form_list">
                        <div class="row">
                            <h3>Daftar TIM</h>
                        </div>

                        <div class="col-3 offset-9">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text span-fixed-width" id="inputGroup-sizing-sm">Cari</span>
                                <input wire:model.live="textcari" type="text" class="form-control" aria-describedby="inputGroup-sizing-sm">
                            </div>

                        </div>

                        <a href="#/" class="pagination-class">{{ $dbTimHds->links(data: ['scrollTo' => false]) }}</a>
                        <table class="table table-sm table-striped table-hover table-sortable">
                            <thead>
                                <tr>
                                    <th class="sort @if ($sortColumn== 'nomer') {{ $sortDirection }} @endif" wire:click="sort('nomer')">Tim</th>
                                    <th class="sort @if ($sortColumn== 'kotas.kota_kabupaten') {{ $sortDirection }} @endif" wire:click="sort('kotas.kota_kabupaten')">Kota</th>
                                    <th>Tgl Awal</th>
                                    <th>Tgl Akhir</th>
                                    <th class="rata-kanan">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dbTimHds as $timhd)
                                <tr>
                                    <td>{{ $timhd->nomer }}</td>
                                    <td>{{ $timhd->joinKota->kota_kabupaten }}</td>
                                    <td>{{ $timhd->tglawal }}</td>
                                    <td>{{ $timhd->tglakhir }}</td>
                                    <td class="rata-kanan">
                                        <a href="#top" wire:click="edit({{ $timhd->id }})" class="badge bg-warning bg-sm"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="#top" wire:click="delete_confirm({{ $timhd->id }})" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#ModalDeleteTim"><i class="bi bi-eraser"></i></a>
                                    </td>
                                </tr>
                                @foreach($timhd->joinTimdt as $timdt)

                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <div wire:ignore.self class="modal fade" id="ModalDeleteTim" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Tim</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin hapus data {{ $nomer }}?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button wire:click="delete()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="ModalDeleteBarang" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Barang</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Anda yakin hapus data {{ $barang }}?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button wire:click="deleteBarang()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>

@assets
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endassets

@script()
<script>
    $(document).ready(function() {
        $('#selectpt').select2();

        var ptidValue = @this.ptid;
        console.log(ptidValue);
        $('#selectpt').val(ptidValue).trigger('change');
    });

    document.addEventListener('livewire:load', function() {
        Livewire.hook('message.received', (message, component) => {
            // Check if the message is related to the 'ptid' property
            if (message.hasOwnProperty('updateQueue') && message.updateQueue.some(update => update.name.includes('ptid'))) {
                let newPtidValue = message.updateQueue.find(update => update.name === 'ptid').value;

                // Update Select2 dropdown with the new value
                $('#selectpt').val(newPtidValue).trigger('change');
            }
        });
    });
</script>
@endscript

<!--
    tutorial select2
    https://www.youtube.com/watch?v=66Kuv1b2_pg
-->