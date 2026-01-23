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
                    $q = mysqli_query($conn, "SELECT chat.*, bpr.nama_bpr FROM chat JOIN bpr ON chat.id_bpr = bpr.id_bpr");
                    while($d = mysqli_fetch_array($q)){
                        $color = ($d['status']=='C'?'secondary':($d['status']=='O'?'primary':($d['status']=='U'?'danger':'success')));
                    ?>
                    <tr>
                        <td>#<?php echo $d['id_chat']; ?></td>
                        <td><?php echo $d['nama_bpr']; ?></td>
                        <td><?php echo $d['judul_masalah']; ?></td>
                        <td><span class="badge badge-<?php echo $color; ?>"><?php echo $d['status']; ?></span></td>
                        <td><a href="detail_chat.php?id=<?php echo $d['id_chat']; ?>" class="btn btn-sm btn-info">Detail Chat</a></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>