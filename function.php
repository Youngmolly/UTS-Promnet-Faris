<?php
$conn = mysqli_connect('localhost', 'root', '', 'produk', 3307);

if (!$conn) {
    die('Koneksi gagal: ' . mysqli_connect_error());
}

// Kode yang menghubungkan php ke database mysql
function query_db($query)
{
    global $conn;
    $result = mysqli_query($conn, $query);
    if (!$result) {
        die('Query gagal: ' . mysqli_error($conn));
    }
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

// Fungsi untuk mencari data
function cari_data($keyword)
{
    global $conn;

    $keyword = mysqli_real_escape_string($conn, $keyword);

    $query = "SELECT * FROM produk WHERE
              kode LIKE '%$keyword%' OR
              nama LIKE '%$keyword%' OR
              kategori LIKE '%$keyword%' OR
              harga LIKE '%$keyword%' OR
              stok LIKE '%$keyword%'";

    return query_db($query);
}

// Fungsi untuk mengupload gambar
function upload_gambar($wajib = true)
{
    if (!isset($_FILES["gambar"]) || $_FILES["gambar"]["error"] == UPLOAD_ERR_NO_FILE) {
        return $wajib ? false : null;
    }

    if ($_FILES["gambar"]["error"] != UPLOAD_ERR_OK || $_FILES["gambar"]["size"] > 5000000) {
        return false;
    }

    $info = getimagesize($_FILES["gambar"]["tmp_name"]);
    if ($info === false) {
        return false;
    }

    if ($info[2] == IMAGETYPE_JPEG) {
        $ekstensi = "jpg";
    } elseif ($info[2] == IMAGETYPE_PNG) {
        $ekstensi = "png";
    } else {
        return false;
    }

    $namaFile = uniqid() . "." . $ekstensi;
    if (!move_uploaded_file($_FILES["gambar"]["tmp_name"], "img/" . $namaFile)) {
        return false;
    }

    return $namaFile;
}

// Fungsi untuk menambah data
function tambah_data($data)
{
    global $conn;
    $query = "INSERT INTO produk (kode, nama, kategori, harga, stok, gambar)
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        die('Query gagal: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssiis",
        $data["kode"],
        $data["nama"],
        $data["kategori"],
        $data["harga"],
        $data["stok"],
        $data["gambar"]
    );
    if (!mysqli_stmt_execute($stmt)) {
        die('Query gagal: ' . mysqli_stmt_error($stmt));
    }

    return mysqli_stmt_affected_rows($stmt);
}

// Fungsi untuk mengubah data yang sudah ada
function edit_data($data)
{
    global $conn;

    if (
        !isset($data["id"], $data["kode"], $data["nama"], $data["kategori"], $data["harga"], $data["stok"]) ||
        !ctype_digit((string) $data["id"]) ||
        !ctype_digit((string) $data["harga"]) ||
        !ctype_digit((string) $data["stok"])
    ) {
        return false;
    }

    $query = "UPDATE produk
              SET kode = ?, nama = ?, kategori = ?, harga = ?, stok = ?, gambar = ?
              WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        die('Query gagal: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param(
        $stmt,
        "sssiisi",
        $data["kode"],
        $data["nama"],
        $data["kategori"],
        $data["harga"],
        $data["stok"],
        $data["gambar"],
        $data["id"]
    );
    if (!mysqli_stmt_execute($stmt)) {
        die('Query gagal: ' . mysqli_stmt_error($stmt));
    }

    return true;
}

// Fungsi untuk menghapus data
function hapus_data($id)
{
    global $conn;

    $stmt = mysqli_prepare($conn, "DELETE FROM produk WHERE id = ?");
    if (!$stmt) {
        die('Query gagal: ' . mysqli_error($conn));
    }

    mysqli_stmt_bind_param($stmt, "i", $id);
    if (!mysqli_stmt_execute($stmt)) {
        die('Query gagal: ' . mysqli_stmt_error($stmt));
    }

    return mysqli_stmt_affected_rows($stmt);
}

// Fungsi untuk memastikan user terautentikasi
function cek_login()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION["session_login"]) || $_SESSION["session_login"] !== true) {
        header("Location: login.php");
        exit;
    }
}

// Fungsi untuk mendaftar
function registrasi($data)
{
    global $conn;

    if (!isset($data["username"], $data["password"], $data["konfirmasi_password"])) {
        return false;
    }

    $username = mysqli_real_escape_string($conn, strtolower($data["username"]));
    $password = $data["password"];
    $konfirmasi_password = $data["konfirmasi_password"];

    $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
        echo "<script>
        alert('Username sudah terdaftar! Gunakan username lainnya');
        </script>";
        return false;
    }

    if ($password !== $konfirmasi_password) {
        echo "<script>
        alert('Konfirmasi password tidak sesuai');
        </script>";
        return false;
    }

    $password = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO user (username, password) VALUES ('$username', '$password')";
    mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}