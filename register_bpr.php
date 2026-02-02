<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran BPR - CV. Royalma</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="font-weight-bold text-primary">Registrasi Mitra BPR</h2>
        <p class="text-muted">Gunakan formulir ini untuk pendaftaran Nasabah BPR</p>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <form method="POST">
                        <input type="text" name="nama" class="form-control mb-3" placeholder="Nama Lengkap BPR" required>
                        <input type="text" name="username" class="form-control mb-3" placeholder="Username untuk Login" required>
                        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                        <button type="submit" name="daftar" class="btn btn-primary btn-block py-2">Daftar Akun BPR</button>
                    </form>
                    <div class="text-center mt-3"><a href="index.php" class="small">Kembali ke Login</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
if(isset($_POST['daftar'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    mysqli_query($conn, "INSERT INTO bpr (nama_bpr, username, password, status_akun) VALUES ('$nama', '$user', '$pass', 'pending')");
    echo "<script>alert('Berhasil! Tunggu konfirmasi Admin IT.'); window.location='index.php';</script>";
}
?>
</body>
</html>