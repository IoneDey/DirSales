<div>
    <style>
        form {
            padding: 20px;
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

    <div class="row g-2">
        <div class="col-6">
            <form id="form_list" action="">
                {{ $timhdLists->links() }}
                <table class="table table-sm table-striped table-hover">
                    <thead>
                        <tr>
                            <th style="width: 5%">Tim</th>
                            <th style="width: 10%">Kota</th>
                            <th style="width: 10%">Tgl Awal</th>
                            <th style="width: 10%">Tgl Akhir</th>
                            <th class="rata-kanan" style="width: 5%">Act</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($timhdLists as $timhd)
                        <tr>
                            <td style="width: 5">{{ $timhd->nomer }}</td>
                            <td style="width: 10%">{{ $timhd->joinKota->kota_kabupaten }}</td>
                            <td style="width: 10%">{{ $timhd->tglawal }}</td>
                            <td style="width: 10%">{{ $timhd->tglakhir }}</td>
                            <td class="rata-kanan" style="width: 5%">
                                <a wire:click="edit({{ $timhd->id }})" class="badge bg-warning bg-sm"><i class="bi bi-pencil-fill"></i></a>
                                <a wire:click="delete_confirm({{ $timhd->id }})" class="badge bg-danger bg-sm" data-bs-toggle="modal" data-bs-target="#exampleModal"><i class="bi bi-eraser"></i></a>
                            </td>
                        </tr>
                        @foreach ($timhd->joinTimdt as $timdt)

                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>

        <div class="col-6">
            <div class="container">
                <div class="row g-2 mb-1">
                    <form id="form_hd" class='mb-1 p-2'>
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text span-fixed-width" id="inputGroup-sizing-sm">Tim</span>
                                    <input wire:model.live='tim' type="text" class="form-control" aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>

                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text span-fixed-width" id="inputGroup-sizing-sm">PT</span>
                                    <select wire:model='ptid' type='text' class="form-control" id="selectpt">
                                        <option value="">Pilih PT</option>
                                        @foreach ($ptLists as $ptList)
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
                                        @foreach ($KotaLists as $KotaList)
                                        <option value="{{ $KotaList->id }}">{{ $KotaList->kota_kabupaten }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text span-fixed-width" id="inputGroup-sizing-sm">Tgl Awal</span>
                                    <input wire:model.live='tglawal' type="date" class="form-control" aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text span-fixed-width" id="inputGroup-sizing-sm">Tgl Akhir</span>
                                    <input wire:model.live='tglakhir' value="2024-01-01" type="date" class="form-control" aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <div class="col-md-12">
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text span-fixed-width" id="inputGroup-sizing-sm">P.I.C</span>
                                    <input wire:model.live='pic' type="text" class="form-control" aria-describedby="inputGroup-sizing-sm">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row g-2">
                    <form id="form_dt" action="">
                        <button wire:click="addDataArray" type="button">Add Row</button>
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 40%;">Barang</th>
                                    <th style="width: 25%;" class="rata-kanan">Hpp</th>
                                    <th style="width: 25%;" class="rata-kanan">Harga Jual</th>
                                    <th style="width: 5%;">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataBarangDetail as $index => $item)
                                <tr>
                                    <td>
                                        <select wire:model="dataBarangDetail.{{ $index }}.barangid" class="form-control" style="width: 100%;" id="selectBarang">
                                            <option value="">Pilih Barang</option>
                                            @foreach ($BarangLists as $BarangList)
                                            <option value="{{ $BarangList->id }}">{{ $BarangList->nama }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input wire:model="dataBarangDetail.{{ $index }}.hpp" type="number" class="form-control rata-kanan" style="width: 100%;"></td>
                                    <td><input wire:model="dataBarangDetail.{{ $index }}.hargajual" type="number" class="form-control rata-kanan" style="width: 100%;"></td>
                                    <td>
                                        <button wire:click="delDataArray({{ $index }})" type="button" class="btn btn-danger"><i class="bi bi-eraser"></i></button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </form>
                    <div>
                        <input wire:click="store()" type="button" class="btn btn-primary" value="Simpan"></input>
                        <input wire:click="clear()" type="button" class="btn btn-secondary" value="Clear"></input>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!--
    tutorial select2
    https://www.youtube.com/watch?v=66Kuv1b2_pg
-->