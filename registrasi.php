<?php
require 'function.php';

if (isset($_POST["register"])) {
    if(registrasi($_POST) > 0) {
        header('Location: login.php');
        exit;
    } else {
        echo mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo"><b>Toko Elektronik</b></div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Buat akun baru</p>
                <form method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <div class="form-group">
                        <label for="konfirmasi_password">Konfirmasi Password</label>
                        <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" placeholder="Masukkan kembali password" required>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary btn-block">Registrasi</button>
                </form>
                <a href="login.php" class="btn btn-outline-secondary btn-block mt-2">Login</a>
            </div>
        </div>
    </div>
</body>
</html>