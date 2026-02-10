<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran BPR - PINtech</title>
    <!-- Bootstrap 4 & Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f0f2f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            width: 100%;
            max-width: 450px; /* Lebar kotak yang ideal */
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="register-card">
    <!-- Header Logo PINtech -->
    <div class="text-center mb-4">
        <img src="assets/img/logo.png" alt="Logo PINtech" class="img-fluid mb-3" style="max-width: 150px;">
        <h3 class="font-weight-bold text-primary">Registrasi Mitra BPR</h3>
        <p class="text-muted small">Gunakan formulir ini untuk pendaftaran Nasabah BPR</p>
    </div>

    <!-- Form Registrasi -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <form method="POST">
                <div class="form-group">
                    <label class="small font-weight-bold text-muted text-uppercase">Nama Lengkap BPR</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-university text-muted"></i></span>
                        </div>
                        <input type="text" name="nama" class="form-control border-left-0" placeholder="Contoh: BPR Sejahtera" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted text-uppercase">Username</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-user text-muted"></i></span>
                        </div>
                        <input type="text" name="username" class="form-control border-left-0" placeholder="Username login" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted text-uppercase">Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white border-right-0"><i class="fas fa-lock text-muted"></i></span>
                        </div>
                        <input type="password" name="password" class="form-control border-left-0" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" name="daftar" class="btn btn-primary btn-block shadow-sm mt-4">
                    DAFTARKAN AKUN BPR
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="index.php" class="text-decoration-none small text-muted">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-4">
        <small class="text-muted">&copy; 2026 PINtech - Mobile Technology</small>
    </div>
</div>

<?php
if(isset($_POST['daftar'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Perintah SQL simpan ke tabel bpr
    $query = "INSERT INTO bpr (nama_bpr, username, password, status_akun) VALUES ('$nama', '$user', '$pass', 'pending')";
    
    if(mysqli_query($conn, $query)){
        echo "<script>alert('Berhasil! Tunggu konfirmasi Admin IT PINtech.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan, silakan coba lagi.');</script>";
    }
}
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>