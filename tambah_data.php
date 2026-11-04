<?php
require "function.php";
cek_login();

if (isset($_POST["submit"])) {
    $gambar = upload_gambar();
    if ($gambar !== false) {
        $_POST["gambar"] = $gambar;
    }

    if ($gambar !== false && tambah_data($_POST) > 0) {
        echo "<script>
                alert('Produk berhasil ditambahkan');
                document.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Pilih gambar JPG atau PNG maksimal 5 MB dan periksa data produk.');
              </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="page-form">

<div class="container">
    <div class="form-card">
        <h3>Tambah Produk Elektronik</h3>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="kode">Kode Produk</label>
                <input type="text" name="kode" id="kode" required placeholder="Contoh: ELK001">
            </div>

            <div class="form-group">
                <label for="nama">Nama Produk</label>
                <input type="text" name="nama" id="nama" required placeholder="Contoh: Laptop ASUS">
            </div>

            <div class="form-group">
                <label for="kategori">Kategori</label>
                <input type="text" name="kategori" id="kategori" required placeholder="Contoh: Laptop / Smartphone / Aksesoris">
            </div>

            <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input type="number" name="harga" id="harga" required placeholder="Contoh: 7500000">
            </div>

            <div class="form-group">
                <label for="stok">Stok</label>
                <input type="number" name="stok" id="stok" required placeholder="Contoh: 10">
            </div>

            <div class="form-group">
                <label for="gambar">Gambar (JPG/PNG, maksimal 5 MB)</label>
                <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png" required>
            </div>

            <div class="button-group">
                <button type="submit" name="submit" class="btn btn-tambah">Tambah Produk</button>
                <a href="index.php" class="btn btn-kembali">Kembali</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>