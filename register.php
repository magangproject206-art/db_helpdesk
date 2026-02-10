<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun Baru - CV. Royalma</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .card { border-radius: 15px; border: none; }
        .btn-success { background-color: #28a745; border: none; border-radius: 8px; }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="font-weight-bold">Daftar Akun Baru</h2>
        <h4 class="text-secondary">CV. Royalma</h4>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg p-4">
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label class="small font-weight-bold">Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold">Username</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                        
                        <!-- PERBAIKAN PILIHAN DIVISI -->
                        <div class="form-group">
                            <label class="small font-weight-bold">Pilih Divisi / Role:</label>
                            <select name="divisi" class="form-control" required>
                                <option value="">-- Pilih Divisi Anda --</option>
                                <option value="bpr">BPR (Nasabah)</option>
                                <option value="pegawai">Pegawai</option>
                                <option value="supervisor">Supervisor</option>
                                <option value="admin">Admin IT</option>
                            </select>
                        </div>

                        <button type="submit" name="daftar" class="btn btn-success btn-block py-2 font-weight-bold mt-4">
                            Kirim Pendaftaran
                        </button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="index.php" class="text-decoration-none small">Kembali ke Login</a>
                    </div>
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

    // Cek apakah pendaftar adalah BPR atau Pegawai/Internal
    if($divisi == 'bpr'){
        // Simpan ke tabel bpr
        $sql = "INSERT INTO bpr (nama_bpr, username, password, status_akun) 
                VALUES ('$nama', '$user', '$pass', 'pending')";
    } else {
        // Simpan ke tabel pegawai (untuk pegawai, supervisor, admin)
        $sql = "INSERT INTO pegawai (nama, role, username, password, status_akun) 
                VALUES ('$nama', '$divisi', '$user', '$pass', 'pending')";
    }
    
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Pendaftaran Berhasil! Silakan tunggu persetujuan Admin.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mendaftar: " . mysqli_error($conn) . "');</script>";
    }
}
?>
</body>
</html>