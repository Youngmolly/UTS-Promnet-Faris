<?php
require "function.php";
cek_login();

// Pagination
$jumlahDataPerHalaman = 7;
$keyword = isset($_GET["keyword"]) ? $_GET["keyword"] : "";

$kondisi = "";
if ($keyword !== "") {
    $keywordEsc = mysqli_real_escape_string($conn, $keyword);
    $kondisi = " WHERE kode LIKE '%$keywordEsc%'
                 OR nama LIKE '%$keywordEsc%'
                 OR kategori LIKE '%$keywordEsc%'
                 OR harga LIKE '%$keywordEsc%'
                 OR stok LIKE '%$keywordEsc%'";
}

$hasilJumlah = query_db("SELECT COUNT(*) AS jumlah FROM produk" . $kondisi);
$jumlahData = $hasilJumlah[0]["jumlah"];
$jumlahHalaman = ceil($jumlahData / $jumlahDataPerHalaman);
if ($jumlahHalaman < 1) {
    $jumlahHalaman = 1;
}

$halamanAktif = isset($_GET["halaman"]) && is_numeric($_GET["halaman"])
    ? floor($_GET["halaman"])
    : 1;
if ($halamanAktif < 1) {
    $halamanAktif = 1;
}
if ($halamanAktif > $jumlahHalaman) {
    $halamanAktif = $jumlahHalaman;
}

$awalData = ($jumlahDataPerHalaman * $halamanAktif) - $jumlahDataPerHalaman;
$produk = query_db("SELECT * FROM produk" . $kondisi . " LIMIT $awalData, $jumlahDataPerHalaman");
$tambahanKeyword = $keyword !== "" ? "&keyword=" . urlencode($keyword) : "";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk - Toko Elektronik</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed page-index">
    <div class="wrapper">
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" aria-label="Buka atau tutup menu">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="index.php" class="brand-link">
                <i class="fas fa-store brand-image ml-3"></i>
                <span class="brand-text font-weight-light">Toko Elektronik</span>
            </a>
            <div class="sidebar">
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <i class="fas fa-user-circle text-white"></i>
                    </div>
                    <div class="info">
                        <span class="d-block text-white">Admin</span>
                    </div>
                </div>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" role="menu">
                        <li class="nav-item">
                            <a href="index.php" class="nav-link active">
                                <i class="nav-icon fas fa-box"></i>
                                <p>Daftar Produk</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="tambah_data.php" class="nav-link">
                                <i class="nav-icon fas fa-plus-circle"></i>
                                <p>Tambah Produk</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <div class="content-wrapper">
            <section class="content pt-4">
                <div class="container-fluid">

    <div class="container">
        <h3>Daftar Produk Toko Elektronik</h3>

        <div class="top-bar">
            <form action="" method="get" class="search-box">
                <input type="text" name="keyword" placeholder="Cari kode, nama, kategori, harga..."
                    value="<?php echo isset($_GET['keyword']) ? $_GET['keyword'] : ''; ?>"
                    autocomplete="off">
                <button type="submit" class="btn btn-cari">Cari</button>
                <?php if (isset($_GET["keyword"]) && $_GET["keyword"] !== ""): ?>
                    <a href="index.php" class="btn btn-kembali">Reset</a>
                <?php endif; ?>
            </form>

            <a href="tambah_data.php" class="btn btn-tambah">+ Tambah Produk</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>ID</th>
                    <th>Kode</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga (Rp)</th>
                    <th>Stok</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $nomor = $awalData + 1; ?>
                <?php foreach ($produk as $data): ?>
                    <tr>
                        <td><?php echo $nomor; ?></td>
                        <td><?php echo $data["id"]; ?></td>
                        <td><?php echo $data["kode"]; ?></td>
                        <td><?php echo $data["nama"]; ?></td>
                        <td><?php echo $data["kategori"]; ?></td>
                        <td><?php echo number_format($data["harga"], 0, ',', '.'); ?></td>
                        <td><?php echo $data["stok"]; ?></td>
                        <td>
                            <?php if (file_exists("img/" . $data["gambar"])): ?>
                                <img src="img/<?php echo $data["gambar"]; ?>" alt="Gambar produk" width="90">
                            <?php else: ?>
                                <?php echo $data["gambar"]; ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit_data.php?id=<?php echo $data["id"]; ?>" class="btn btn-edit">Edit</a>
                            <a href="hapus_data.php?id=<?php echo $data["id"]; ?>" class="btn btn-hapus" onclick="return confirm('Yakin ingin menghapus produk ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php $nomor++; ?>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($halamanAktif > 1): ?>
                <a href="?halaman=<?php echo ($halamanAktif - 1) . $tambahanKeyword; ?>">&laquo; Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $jumlahHalaman; $i++): ?>
                <?php if ($i == $halamanAktif): ?>
                    <span class="active"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?halaman=<?php echo $i . $tambahanKeyword; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($halamanAktif < $jumlahHalaman): ?>
                <a href="?halaman=<?php echo ($halamanAktif + 1) . $tambahanKeyword; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
                    </div>
                </div>
                </section>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>