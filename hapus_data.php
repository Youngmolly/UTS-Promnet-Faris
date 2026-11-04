<?php
require 'function.php';
cek_login();

$id = isset($_GET['id']) && is_string($_GET['id']) ? $_GET['id'] : '';
if (!ctype_digit($id) || $id < 1) {
  exit('ID produk tidak valid.');
}

if (hapus_data($id) > 0) {
  echo "<script>alert('Produk berhasil dihapus'); document.location.href = 'index.php';</script>";
} else {
  echo "<script>alert('Produk tidak ditemukan atau gagal dihapus'); document.location.href = 'index.php';</script>";
}