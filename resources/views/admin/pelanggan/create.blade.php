<!DOCTYPE html>
<html>
<head>
    <title>Tambah Pelanggan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

    <div class="container">
        <h1 class="mb-4">Tambah Pelanggan</h1>

        <form action="{{ url('/pelanggan') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Nama Pelanggan</label>
                <input type="text" name="NamaPelanggan" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Alamat</label>
                <textarea name="Alamat" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor Telepon</label>
                <input type="text" name="NomorTelepon" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ url('/pelanggan') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

</body>
</html>
