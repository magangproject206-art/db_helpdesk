<?php include 'config.php'; include 'menu.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-dark text-white">Monitoring Semua Komplain</div>
        <div class="card-body p-0"> <!-- p-0 agar tabel penuh -->
            <table class="table table-bordered table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th>ID</th>
                        <th>BPR Pelapor</th>
                        <th>Judul Masalah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $id_user = $_SESSION['id_user'];
                    $role = $_SESSION['role'];

                    if($role == 'bpr') {
                        // BPR hanya melihat komplain yang id_bpr-nya adalah milik dia sendiri
                        $sql = "SELECT chat.*, bpr.nama_bpr FROM chat 
                                JOIN bpr ON chat.id_bpr = bpr.id_bpr 
                                WHERE chat.id_bpr = '$id_user'
                                ORDER BY chat.timestamp DESC";
                    } else {
                        // Pegawai, Admin, Supervisor melihat SEMUA komplain (seperti pemilik akun WA)
                        $sql = "SELECT chat.*, bpr.nama_bpr FROM chat 
                                JOIN bpr ON chat.id_bpr = bpr.id_bpr 
                                ORDER BY chat.timestamp DESC";
                    }
                    $res = mysqli_query($conn, $sql);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>