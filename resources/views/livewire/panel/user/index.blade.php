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
                        <div class="col-6 g-1">
                            <div class="form-floating mb-1">
                                <input wire:model="name" type="text" class="form-control" id="" placeholder="name">
                                <label for="name">Nama</label>
                            </div>
                            @error('name')
                            <span style="font-size: smaller; color: red;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-6 g-1">
                            <div class="form-floating mb-1">
                                <input wire:model="username" type="text" class="form-control" id="alamat" placeholder="user name">
                                <label for="username">Username</label>
                            </div>
                            @error('username')
                            <span style="font-size: smaller; color: red;">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 g-1">
                            <div class="form-floating mb-1">
                                <input wire:model="email" type="email" class="form-control" id="email" placeholder="email">
                                <label for="email">Email</label>
                            </div>
                            @error('email')
                            <span style="font-size: smaller; color: red;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-6 g-1">
                            <div class="form-floating mb-1">
                                <select wire:model="roles" class="form-control" id="roles" placeholder="roles">
                                    <option value="SUPERVISOR">SUPERVISOR</option>
                                    <option value="MANAGEMENT">MANAGEMENT</option>
                                    <option value="SPV ADMIN">ADMIN 1</option>
                                    <option value="SPV LOCK">ADMIN 2</option>
                                    <option value="LOCK">LOCK</option>
                                </select>
                                <label for="roles">Roles</label>
                            </div>
                            @error('roles')
                            <span style="font-size: smaller; color: red;">{{ $message }}</span><br>
                            @enderror
                        </div>

                        <div class="col-6 g-1">
                            <div class="form-floating mb-1">
                                <input wire:model="password" @if($isUpdate) disabled @endif type="password" class="form-control" id="password" placeholder="password">
                                <label for="password">Password</label>
                            </div>
                            @error('password')
                            <span style="font-size: smaller; color: red;">{{ $message }}</span><br>
                            @enderror
                        </div>

                        <div class="col-6 g-1">
                            <div class="form-floating mb-1">
                                <input wire:model="passwordbaru" @if(!$isUpdate) disabled @endif type="password" class="form-control" id="passwordbaru" placeholder="password baru">
                                <label for="passwordbaru">Password Baru</label>
                            </div>
                            @error('passwordbaru')
                            <span style="font-size: smaller; color: red;">{{ $message }}</span><br>
                            @enderror
                        </div>

                        <div class="input-group mb-1">
                            <label class="input-group-text" for="inputGroupFile01">Profile Pic</label>
                            <input wire:model="image" accept="image/png, image/jpeg" type="file" class="form-control" id="inputGroupFile01">
                        </div>
                        @error('image')
                        <span style="font-size: smaller; color: red;">{{ $message }}</span><br>
                        @enderror
                        <div wire:loading wire:target="image">Uploading...</div>

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

            <div class="row justify-content-center">
                <div class="col-6 mb-1">
                    <input class="border rounded" wire:model.live.debounce.500ms="cari" type="text" id="cari" placeholder="cari....">
                </div>
            </div>

            <div>
                @foreach ($datas as $data)
                @include('livewire.panel.user.card')
                @endforeach
            </div>
            <div class="row justify-content-center">
                <div class="col-6 mb-1">
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
                        Anda yakin hapus data {{ $name }}?
                    </div>
                    <div class="modal-footer">
                        <button wire:click="clear" type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                        <button wire:click="delete()" type="button" class="btn btn-primary" data-bs-dismiss="modal">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>