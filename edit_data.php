<?php
require "function.php";
cek_login();

$id = isset($_GET["id"]) && is_string($_GET["id"]) ? $_GET["id"] : "";
if (!ctype_digit($id) || $id < 1) {
    exit("ID produk tidak valid.");
}

$hasil = query_db("SELECT * FROM produk WHERE id = " . $id);
if (!$hasil) {
    http_response_code(404);
    exit("Produk tidak ditemukan.");
}

$produk = $hasil[0];

if (isset($_POST["submit"])) {
    $gambar = upload_gambar(false);
    if ($gambar === null) {
        $_POST["gambar"] = $produk["gambar"];
    } else {
        $_POST["gambar"] = $gambar;
    }

    if ($gambar !== false && edit_data($_POST)) {
        echo "<script>
                alert('Produk berhasil diubah');
                document.location.href = 'index.php';
              </script>";
        exit;
    }

    echo "<script>alert('Data produk tidak valid atau gambar bukan JPG/PNG maksimal 5 MB.');</script>";
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Produk</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="page-form">
<div class="container">
    <div class="form-card">
        <h3>Edit Data Produk</h3>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $produk["id"] ?>">

            <div class="form-group">
                <label for="kode">Kode Produk</label>
                <input type="text" name="kode" id="kode" required value="<?= $produk["kode"] ?>">
            </div>

            <div class="form-group">
                <label for="nama">Nama Produk</label>
                <input type="text" name="nama" id="nama" required value="<?= $produk["nama"] ?>">
            </div>

            <div class="form-group">
                <label for="kategori">Kategori</label>
                <input type="text" name="kategori" id="kategori" required value="<?= $produk["kategori"] ?>">
            </div>

            <div class="form-group">
                <label for="harga">Harga (Rp)</label>
                <input type="number" name="harga" id="harga" min="0" required value="<?= $produk["harga"] ?>">
            </div>

            <div class="form-group">
                <label for="stok">Stok</label>
                <input type="number" name="stok" id="stok" min="0" required value="<?= $produk["stok"] ?>">
            </div>

            <div class="form-group">
                <label for="gambar">Pilih gambar baru (kosongkan jika tidak ingin mengganti)</label>
                <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png">
                <img src="img/<?= $produk["gambar"] ?>" alt="Gambar produk saat ini" width="120">
                <p>Foto lama (akan diganti jika memilih gambar baru).</p>
            </div>

            <div class="button-group">
                <button type="submit" name="submit" class="btn btn-ubah">Simpan Perubahan</button>
                <a href="index.php" class="btn btn-kembali">Kembali</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>