<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Staff - CV. Royalma</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-dark">
<div class="container mt-5">
    <div class="text-center mb-4 text-white">
        <h2 class="font-weight-bold">Portal Registrasi Internal</h2>
        <p class="opacity-75">Khusus Karyawan & Pimpinan CV. Royalma</p>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow border-0">
                <div class="card-body p-4">
                    <form method="POST">
                        <input type="text" name="nama" class="form-control mb-3" placeholder="Nama Lengkap Staff" required>
                        <input type="text" name="username" class="form-control mb-3" placeholder="Username Staff" required>
                        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                        <select name="role" class="form-control mb-3" required>
                            <option value="pegawai">Pegawai</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="admin">Admin IT</option>
                        </select>
                        <button type="submit" name="daftar" class="btn btn-dark btn-block py-2">Daftar Akun Staff</button>
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
    $role = $_POST['role'];
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    mysqli_query($conn, "INSERT INTO pegawai (nama, role, username, password, status_akun) VALUES ('$nama', '$role', '$user', '$pass', 'pending')");
    echo "<script>alert('Pendaftaran Staff Berhasil Terkirim!'); window.location='index.php';</script>";
}
?>
</body>
</html>