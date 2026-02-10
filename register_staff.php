<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Staff - PINtech</title>
    <!-- Bootstrap 4 & Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #212529; /* Tema gelap untuk internal */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }
        .btn-dark {
            background-color: #343a40;
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: bold;
        }
        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #ced4da;
        }
    </style>
</head>
<body>

<div class="register-card">
    <!-- Header Logo PINtech -->
    <div class="text-center mb-4 text-white">
        <img src="assets/img/logo.png" alt="Logo PINtech" class="img-fluid mb-3" style="max-width: 150px; filter: drop-shadow(0px 0px 5px rgba(255,255,255,0.2));">
        <h3 class="font-weight-bold">Portal Registrasi Internal</h3>
        <p class="opacity-75 small">Khusus Karyawan & Pimpinan PINtech</p>
    </div>

    <!-- Form Registrasi Staff -->
    <div class="card shadow-lg border-0">
        <div class="card-body p-4">
            <form method="POST">
                <div class="form-group">
                    <label class="small font-weight-bold text-muted text-uppercase">Nama Lengkap Staff</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user-tie text-muted"></i></span>
                        </div>
                        <input type="text" name="nama" class="form-control" placeholder="Nama Lengkap" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted text-uppercase">Username Staff</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-at text-muted"></i></span>
                        </div>
                        <input type="text" name="username" class="form-control" placeholder="Username login" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted text-uppercase">Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key text-muted"></i></span>
                        </div>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="small font-weight-bold text-muted text-uppercase">Pilih Role / Jabatan</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-briefcase text-muted"></i></span>
                        </div>
                        <select name="role" class="form-control" required>
                            <option value="pegawai">Pegawai</option>
                            <option value="supervisor">Supervisor</option>
                            <option value="admin">Admin IT</option>
                        </select>
                    </div>
                </div>

                <button type="submit" name="daftar" class="btn btn-dark btn-block shadow-sm mt-4">
                    DAFTARKAN AKUN STAFF
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="index.php" class="text-decoration-none small text-primary font-weight-bold">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Login
                </a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-4 text-white-50">
        <small>&copy; 2026 PINtech - Mobile Technology</small>
    </div>
</div>

<?php
if(isset($_POST['daftar'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    $role = $_POST['role'];
    
    // Perintah SQL simpan ke tabel pegawai
    $query = "INSERT INTO pegawai (nama, role, username, password, status_akun) VALUES ('$nama', '$role', '$user', '$pass', 'pending')";
    
    if(mysqli_query($conn, $query)){
        echo "<script>alert('Pendaftaran Staff Berhasil Terkirim! Mohon tunggu konfirmasi Admin.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Gagal mengirim pendaftaran, silakan coba lagi.');</script>";
    }
}
?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>