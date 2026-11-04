<?php
session_start();

require 'function.php';

if (isset($_SESSION['session_login']) && $_SESSION['session_login'] === true) {
    header('Location: index.php');
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username = '$username' ");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['session_login'] = true;
            $_SESSION['username'] = $row['username'];
            header('Location: index.php');
            exit;
        } else {
            echo "<script>alert('Password yang kamu masukkan salah!');</script>";
        }
    } else {
        echo "<script>alert('Username yang kamu masukkan salah atau tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2.0/dist/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo"><b>Toko Elektronik</b></div>
        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Silakan masuk untuk melanjutkan</p>
                <form method="post">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Masukkan username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                    <button type="submit" name="login" class="btn btn-primary btn-block">Login</button>
                </form>
                <a href="registrasi.php" class="btn btn-outline-secondary btn-block mt-2">Registrasi</a>
            </div>
        </div>
    </div>
</body>
</html>