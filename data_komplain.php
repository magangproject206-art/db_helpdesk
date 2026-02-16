<?php 
include 'config.php'; 
include 'menu.php'; 

// Pastikan sudah login
if(!isset($_SESSION['id_user'])) { echo "<script>window.location='index.php';</script>"; exit(); }

$my_id = $_SESSION['id_user'];
$my_role = $_SESSION['role'];
?>

<div class="container-fluid px-4">
    <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-dark text-white py-3">
            <h6 class="m-0 font-weight-bold">Monitoring Semua Komplain</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr class="small text-muted text-uppercase">
                            <th class="pl-4">ID</th>
                            <th>BPR Pelapor</th>
                            <th>Judul Masalah</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        // LOGIKA FILTER: BPR hanya lihat miliknya sendiri
                        if($my_role == 'bpr') {
                            $sql = "SELECT chat.*, bpr.nama_bpr FROM chat 
                                    JOIN bpr ON chat.id_bpr = bpr.id_bpr 
                                    WHERE chat.id_bpr = '$my_id' 
                                    ORDER BY timestamp DESC";
                        } else {
                            // Supervisor, Admin, dan Pegawai melihat SEMUA laporan
                            $sql = "SELECT chat.*, bpr.nama_bpr FROM chat 
                                    JOIN bpr ON chat.id_bpr = bpr.id_bpr 
                                    ORDER BY timestamp DESC";
                        }

                        $query = mysqli_query($conn, $sql);

                        if(mysqli_num_rows($query) > 0) {
                            while($row = mysqli_fetch_array($query)){
                                // Penentuan warna Badge Status
                                $st = $row['status'];
                                $badge_color = ($st=='C'?'secondary':($st=='O'?'primary':($st=='U'?'danger':'success')));
                        ?>
                            <tr>
                                <td class="pl-4 text-muted small">#<?php echo $row['id_chat']; ?></td>
                                <td class="font-weight-bold"><?php echo $row['nama_bpr']; ?></td>
                                <td><?php echo $row['judul_masalah']; ?></td>
                                <td class="text-center">
                                    <span class="badge badge-<?php echo $badge_color; ?> px-3 py-2">
                                        <?php echo $st; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="detail_chat.php?id=<?php echo $row['id_chat']; ?>" class="btn btn-sm btn-info shadow-sm px-3" style="border-radius: 8px;">
                                        Detail Chat
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            } 
                        } else {
                            // Tampilan jika tidak ada data
                            echo "<tr><td colspan='5' class='text-center py-5 text-muted small'>Belum ada laporan komplain yang masuk.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>