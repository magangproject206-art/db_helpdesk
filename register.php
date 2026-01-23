<?php include 'config.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Daftar Akun - CV. Royalma</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="font-weight-bold">Daftar Akun Baru</h2>
        <h4 class="text-secondary">CV. Royalma</h4>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <label>Pilih Divisi / Role:</label>
                            <select name="divisi" class="form-control" required>
                                <option value="bpr">Pilih Divisi anda/option>
                                <option value="pegawai">Pegawai Internal</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="admin">Admin IT</option>
                            </select>
                        </div>
                        <button type="submit" name="daftar" class="btn btn-success btn-block py-2">Kirim Pendaftaran</button>
                    </form>
                    <div class="text-center mt-3"><a href="index.php">Kembali ke Login</a></div>
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
    $divisi = $_POST['divisi'];

    if($divisi == 'bpr'){
        // Masuk ke tabel BPR
        $query = "INSERT INTO bpr (nama_bpr, username, password, status_akun) VALUES ('$nama', '$user', '$pass', 'pending')";
    } else {
        // Masuk ke tabel Pegawai
        $query = "INSERT INTO pegawai (nama, role, username, password, status_akun) VALUES ('$nama', '$divisi', '$user', '$pass', 'pending')";
    }
    
    if(mysqli_query($conn, $query)){
        echo "<script>alert('Pendaftaran Berhasil! Tunggu konfirmasi Admin.'); window.location='index.php';</script>";
    }
}
?>
</body>
</html>