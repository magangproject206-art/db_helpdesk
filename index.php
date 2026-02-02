<?php 
include 'config.php'; 

// Logika Login
if(isset($_POST['login'])){
    $user = mysqli_real_escape_string($conn, $_POST['username']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);

    // 1. Cek di tabel PEGAWAI (Admin, Supervisor, Pegawai)
    $cek_pegawai = mysqli_query($conn, "SELECT * FROM pegawai WHERE username='$user' AND password='$pass'");
    
    if(mysqli_num_rows($cek_pegawai) > 0){
        $data = mysqli_fetch_assoc($cek_pegawai);
        if($data['status_akun'] == 'aktif'){
            $_SESSION['id_user'] = $data['id_pegawai'];
            $_SESSION['nama']    = $data['nama'];
            $_SESSION['role']    = $data['role']; // admin, supervisor, atau pegawai
            
            echo "<script>alert('Login Berhasil! Selamat Datang Staf.'); window.location='dashboard.php';</script>";
            exit;
        } else {
            echo "<script>alert('Akun Staf Anda belum diaktifkan oleh Admin.');</script>";
        }
    } else {
        // 2. Jika tidak ada di pegawai, cek di tabel BPR (Nasabah)
        $cek_bpr = mysqli_query($conn, "SELECT * FROM bpr WHERE username='$user' AND password='$pass'");
        
        if(mysqli_num_rows($cek_bpr) > 0){
            $data = mysqli_fetch_assoc($cek_bpr);
            if($data['status_akun'] == 'aktif'){
                $_SESSION['id_user'] = $data['id_bpr'];
                $_SESSION['nama']    = $data['nama_bpr'];
                $_SESSION['role']    = 'bpr';
                
                echo "<script>alert('Login Berhasil! Selamat Datang Mitra BPR.'); window.location='dashboard.php';</script>";
                exit;
            } else {
                echo "<script>alert('Akun BPR Anda masih menunggu persetujuan Admin IT.');</script>";
            }
        } else {
            // 3. Jika username atau password tidak ditemukan di kedua tabel
            echo "<script>alert('Username atau Password salah!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Helpdesk CV. Royalma</title>
    <!-- Bootstrap 4 & Google Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
        }
        .card {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }
        .btn-primary {
            background-color: #3498db;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            padding: 12px;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .reg-link {
            font-size: 0.85rem;
            color: #7f8c8d;
            text-decoration: none;
            transition: 0.2s;
        }
        .reg-link:hover {
            color: #3498db;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <!-- Logo / Judul -->
            <div class="text-center mb-4">
                <h2 class="font-weight-bold text-dark">Aplikasi Helpdesk</h2>
                <h5 class="text-secondary">CV. Royalma</h5>
            </div>

            <!-- Login Card -->
            <div class="card shadow-lg">
                <div class="card-body p-4">
                    <h5 class="text-center font-weight-bold mb-4">Silakan Masuk</h5>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label class="small font-weight-bold">USERNAME</label>
                            <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                        </div>
                        <div class="form-group">
                            <label class="small font-weight-bold">PASSWORD</label>
                            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary btn-block shadow mt-4">MASUK KE SISTEM</button>
                    </form>
                    
                    <hr class="my-4">
                    
                    <!-- PENDAFTARAN TERPISAH (Sesuai Catatan) -->
                    <div class="text-center">
                        <p class="small text-muted mb-2">Belum punya akun?</p>
                        <div class="d-flex justify-content-around">
                            <a href="register_bpr.php" class="reg-link font-weight-bold">
                                <i class="fas fa-university mr-1"></i> Daftar BPR
                            </a>
                            <div style="border-left: 1px solid #ddd;"></div>
                            <a href="register_staff.php" class="reg-link font-weight-bold">
                                <i class="fas fa-user-tie mr-1"></i> Daftar Staff
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <p class="text-center mt-4 text-muted small">
                &copy; 2026 CV. Royalma - All Rights Reserved
            </p>
        </div>
    </div>
</div>

<!-- Font Awesome for Icons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
</body>
</html>