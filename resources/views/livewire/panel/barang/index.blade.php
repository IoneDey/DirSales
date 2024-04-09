<div>
  <style>
    .input-group-label {
      flex: 0 0 150px;
      text-align: left;
      padding-right: 10px;
      border: 1px solid #ced4da;
      border-right: none;
      border-radius: 0.25rem 0 0 0.25rem;
      background-color: #98999a;
    }
  </style>

  <style>
    /* CSS untuk menambahkan efek bayangan pada form */
    form {
      padding: 20px;
      /* Beri padding untuk ruang di sekitar konten form */
      border: 1px solid #ccc;
      /* Tambahkan border sebagai pemisah dari elemen lain */
      border-radius: 8px;
      /* Beri sudut yang melengkung pada form */
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      /* Tambahkan efek bayangan */
    }
  </style>

  <form action="/panel/barang" method="post">
    @csrf
    <div class="input-group input-group-sm mb-1">
      <div class="input-group-label">Kode</div>
      <input value="{{ old('kode') }}" name="kode" required type="text" class="form-control @error('kode') is-invalid @enderror" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm" autofocus>
      @error('kode')
      <div class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>

    <div class="input-group input-group-sm mb-1">
      <div class="input-group-label">Nama</div>
      <input value="{{ old('nama') }}" name="nama" required type="text" class="form-control @error('nama') is-invalid @enderror" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
      @error('nama')
      <div class="invalid-feedback">
        {{ $message }}
      </div>
      @enderror
    </div>


    <button class="btn btn-primary btn-sm" type="submit"><i class="bi bi-hand-thumbs-up"></i> Simpan</button>
  </form>


  <form action="">
    @if($errors->any())
    <div class="alert alert-danger" id="error-message">
      {{ $errors->first('msg') }}
    </div>
    <script>
      setTimeout(function() {
        var errorMessage = document.getElementById('error-message');
        if (errorMessage) {
          errorMessage.remove();
        }
      }, 3500);
    </script>
    @endif

    @if(session()->has('ok'))
    <div class="alert alert-danger" id="ok-message">
      {{ session('ok') }}
    </div>
    <script>
      setTimeout(function() {
        var errorMessage = document.getElementById('ok-message');
        if (errorMessage) {
          errorMessage.remove();
        }
      }, 3500);
    </script>
    @endif

    <table class="table table-sm table-striped table-hover">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Kode</th>
          <th scope="col">Nama</th>
          <th class="rata-kanan" scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        foreach ( $barangs as $barang )
        <tr>
          <th scope="row">1</th>
          <td>kode</td>
          <td>nama</td>
          <td class="rata-kanan">
            <a class="badge bg-warning" href="/panel/barang/edit"><i class="bi bi-pencil-fill"></i></a>
            <form action="/panel/barang/" method="POST" style="display: inline;">
              @csrf
              @method('DELETE')
              <button type="submit" class="badge bg-danger border-0" onclick="return confirm('Apakah Anda yakin ingin menghapus data barang->kode?')"><i class="bi bi-eraser"></i></button>
            </form>
          </td>
        </tr>
        endforeach
      </tbody>
    </table>

    <p>Menampilkan data.</p>

    <div class="text-center" style="margin-top: 10px;">
      <div class="pagination">
        $barangs->onEachSide(0)->links()
      </div>
    </div>
  </form>
</div>