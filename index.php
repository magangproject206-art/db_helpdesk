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
            echo "<script>alert('Selamat Datang Pegawai!'); window.location='dashboard.php';</script>";
            exit;
        } else {
            echo "<script>alert('Akun Pegawai belum aktif.');</script>";
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
                echo "<script>alert('Selamat Datang BPR!'); window.location='dashboard.php';</script>";
                exit;
            } else {
                echo "<script>alert('Akun BPR Anda belum disetujui Admin.');</script>";
            }
        } else {
            echo "<script>alert('Username atau Password salah!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Helpdesk - CV. Royalma</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="text-center mb-4">
        <h2 class="font-weight-bold">Aplikasi Helpdesk</h2>
        <h4 class="text-secondary">CV. Royalma</h4>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header text-center bg-white py-3"><h4>Login User</h4></div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-primary btn-block py-2">Masuk Ke Aplikasi</button>
                    </form>
                    <hr>
                    <p class="text-center">Belum punya akun? <a href="register.php">Daftar Sekarang</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>