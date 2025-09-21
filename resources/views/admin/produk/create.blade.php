<!DOCTYPE html>
<html>
<head>
    <title>Tambah Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

    <div class="container">
        <h1 class="mb-4">Tambah Produk</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Ups!</strong> Ada masalah dengan input kamu.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('produk.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="NamaProduk" class="form-label">Nama Produk</label>
                <input type="text" name="NamaProduk" class="form-control" placeholder="Masukkan nama produk" required>
            </div>

            <div class="mb-3">
                <label for="Harga" class="form-label">Harga</label>
                <input type="number" name="Harga" class="form-control" placeholder="Masukkan harga" required>
            </div>

            <div class="mb-3">
                <label for="Stok" class="form-label">Stok</label>
                <input type="number" name="Stok" class="form-control" placeholder="Masukkan jumlah stok" required>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

</body>
</html>
