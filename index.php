<?php 
include 'config.php'; 

// Logika Login
if(isset($_POST['login'])){
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    // 1. Cek di tabel PEGAWAI
    $cek_pegawai = mysqli_query($conn, "SELECT * FROM pegawai WHERE username='$user' AND password='$pass'");
    if(mysqli_num_rows($cek_pegawai) > 0){
        $data = mysqli_fetch_assoc($cek_pegawai);
        if($data['status_akun'] == 'aktif'){
            $_SESSION['id_user'] = $data['id_pegawai'];
            $_SESSION['nama']    = $data['nama'];
            $_SESSION['role']    = $data['role'];
            echo "<script>alert('Login Berhasil! Selamat datang di PINTech Helpdesk.'); window.location='dashboard.php';</script>";
            exit;
        } else {
            echo "<script>alert('Akun staf Anda belum diaktifkan oleh Admin IT.');</script>";
        }
    } else {
        // 2. Cek di tabel BPR
        $cek_bpr = mysqli_query($conn, "SELECT * FROM bpr WHERE username='$user' AND password='$pass'");
        if(mysqli_num_rows($cek_bpr) > 0){
            $data = mysqli_fetch_assoc($cek_bpr);
            if($data['status_akun'] == 'aktif'){
                $_SESSION['id_user'] = $data['id_bpr'];
                $_SESSION['nama']    = $data['nama_bpr'];
                $_SESSION['role']    = 'bpr';
                echo "<script>alert('Login Berhasil! Selamat datang mitra BPR.'); window.location='dashboard.php';</script>";
                exit;
            } else {
                echo "<script>alert('Akun BPR belum disetujui oleh Admin IT PINTech.');</script>";
            }
        } else {
            echo "<script>alert('Username atau Password salah! Periksa kembali ketikan Anda.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PINtech E-Helpdesk</title>
    <!-- Bootstrap 4 & Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background: #f0f2f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 400px;
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
        /* Style untuk show password */
        .password-toggle {
            cursor: pointer;
        }
        .reg-link { font-size: 0.8rem; color: #6c757d; text-decoration: none; font-weight: 600;}
        .reg-link:hover { color: #007bff; text-decoration: underline; }
    </style>
</head>
<body>

<div class="login-card">
    <!-- Header Logo PINtech -->
    <div class="text-center mb-4">
        <img src="assets/img/logo.png" alt="Logo PINtech" class="img-fluid mb-2" style="max-width: 150px;">
        <h5 class="font-weight-bold text-dark">E-Helpdesk Pelayanan</h5>
        <small class="text-muted">PINtech - Mobile Technology</small>
    </div>

    <!-- Login Form -->
    <div class="card shadow-lg">
        <div class="card-body p-4">
            <h6 class="text-center mb-4 font-weight-bold">Silakan Masuk ke Akun Anda</h6>
            <form method="POST">
                <!-- Username -->
                <div class="form-group">
                    <label class="small font-weight-bold text-muted text-uppercase">Username</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fas fa-user text-muted"></i></span>
                        </div>
                        <input type="text" name="username" class="form-control border-left-0" placeholder="Masukkan username" required>
                    </div>
                </div>

                <!-- Password dengan fitur Show Password -->
                <div class="form-group mb-1">
                    <label class="small font-weight-bold text-muted text-uppercase">Password</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="fas fa-lock text-muted"></i></span>
                        </div>
                        <input type="password" name="password" id="passwordInput" class="form-control border-left-0 border-right-0" placeholder="Masukkan password" required>
                        <div class="input-group-append">
                            <span class="input-group-text bg-white border-left-0 password-toggle" onclick="togglePassword()">
                                <i class="fas fa-eye text-muted" id="eyeIcon"></i>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Fitur Lupa Kata Sandi -->
                <div class="text-right mb-4">
                    <a href="javascript:void(0)" onclick="alert('Silakan hubungi Administrator IT PINtech melalui WhatsApp atau datang ke ruang IT untuk mereset kata sandi Anda.')" class="small text-muted">Lupa kata sandi?</a>
                </div>

                <button type="submit" name="login" class="btn btn-primary btn-block shadow-sm">
                    MASUK KE SISTEM
                </button>
            </form>
            
            <hr class="my-4">
            
            <div class="text-center">
                <p class="small text-muted mb-2">Belum punya akses?</p>
                <div class="d-flex justify-content-center">
                    <a href="register_bpr.php" class="reg-link mr-3">
                        <i class="fas fa-university"></i> Daftar BPR
                    </a>
                    <span class="text-muted small">|</span>
                    <a href="register_staff.php" class="reg-link ml-3">
                        <i class="fas fa-user-tie"></i> Daftar Staff
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="text-center mt-4">
        <small class="text-muted">&copy; 2026 PINtech - All Rights Reserved</small>
    </div>
</div>

<!-- JavaScript untuk Fitur Lihat Password -->
<script>
    function togglePassword() {
        const passwordInput = document.getElementById("passwordInput");
        const eyeIcon = document.getElementById("eyeIcon");
        
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>