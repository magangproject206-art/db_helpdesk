<?php
include 'config.php';
include 'menu.php';

// Pastikan sudah login
if(!isset($_SESSION['id_user'])) { header("location:index.php"); exit(); }

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];

// LOGIKA KPI: Jika BPR, hitung data dia saja. Jika Staff/Admin, hitung semua.
$filter_kpi = ($role == 'bpr') ? "WHERE id_bpr = '$id_user'" : "";

$res_c = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chat $filter_kpi " . ($filter_kpi ? "AND" : "WHERE") . " status='C'"))['total'];
$res_o = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chat $filter_kpi " . ($filter_kpi ? "AND" : "WHERE") . " status='O'"))['total'];
$res_u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chat $filter_kpi " . ($filter_kpi ? "AND" : "WHERE") . " status='U'"))['total'];
$res_f = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chat $filter_kpi " . ($filter_kpi ? "AND" : "WHERE") . " status='F'"))['total'];
?>

<div class="container-fluid px-4">
    <!-- HEADER & QUICK ACTIONS -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card bg-primary text-white p-4 border-0 shadow-sm" style="border-radius: 15px;">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h3 class="mb-0 font-weight-bold text-white">Halo, <?php echo $_SESSION['nama']; ?>!</h3>
                        <p class="mb-0 opacity-75">Sistem Monitoring Komplain BPR - PINTech.</p>
                    </div>
                    <div class="py-2">
                        <button class="btn btn-light font-weight-bold shadow-sm mr-2 text-primary" data-toggle="modal" data-target="#modalLapor">
                            <i class="fas fa-plus-circle mr-1"></i> Buat Laporan Baru
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- WIDGET KPI -->
    <div class="row mb-4 text-center">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 5px solid #6c757d !important;">
                <div class="card-body">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">C (Created)</div>
                    <h2 class="font-weight-bold mb-0"><?php echo $res_c; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 5px solid #007bff !important;">
                <div class="card-body text-primary">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">O (Open)</div>
                    <h2 class="font-weight-bold mb-0"><?php echo $res_o; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 5px solid #dc3545 !important;">
                <div class="card-body text-danger">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">U (Unfinish)</div>
                    <h2 class="font-weight-bold mb-0"><?php echo $res_u; ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; border-left: 5px solid #28a745 !important;">
                <div class="card-body text-success">
                    <div class="text-muted small font-weight-bold text-uppercase mb-1">F (Finish)</div>
                    <h2 class="font-weight-bold mb-0"><?php echo $res_f; ?></h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- TABEL KOMPLAIN -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-history mr-2 text-primary"></i> Laporan & Progres Penanganan</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr class="small text-muted text-uppercase">
                                    <th class="pl-4"><?php echo ($role == 'bpr') ? 'Masalah' : 'BPR Pelapor'; ?></th>
                                    <th>Petugas (Staf)</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right pr-4">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // QUERY DATA: BPR hanya lihat miliknya, lainnya lihat semua
                                if($role == 'bpr') {
                                    $sql = "SELECT chat.*, bpr.nama_bpr, pegawai.nama as nama_staf 
                                            FROM chat 
                                            JOIN bpr ON chat.id_bpr = bpr.id_bpr 
                                            LEFT JOIN pegawai ON chat.id_pegawai = pegawai.id_pegawai 
                                            WHERE chat.id_bpr = '$id_user' 
                                            ORDER BY timestamp DESC LIMIT 10";
                                } else {
                                    $sql = "SELECT chat.*, bpr.nama_bpr, pegawai.nama as nama_staf 
                                            FROM chat 
                                            JOIN bpr ON chat.id_bpr = bpr.id_bpr 
                                            LEFT JOIN pegawai ON chat.id_pegawai = pegawai.id_pegawai 
                                            ORDER BY timestamp DESC LIMIT 10";
                                }
                                
                                $chat_res = mysqli_query($conn, $sql);
                                if(mysqli_num_rows($chat_res) > 0){
                                    while($c = mysqli_fetch_array($chat_res)){
                                        $badge = ($c['status']=='C'?'secondary':($c['status']=='O'?'primary':($c['status']=='U'?'danger':'success')));
                                        
                                        // LOGIKA NOTIFIKASI OTOMATIS (SLA 2 JAM)
                                        $notif_sla = "";
                                        if($c['status'] == 'C'){
                                            $waktu_lapor = strtotime($c['timestamp']);
                                            $diff = (time() - $waktu_lapor) / 3600; // Hitung jam
                                            if($diff > 2){
                                                $notif_sla = '<br><span class="badge badge-danger small" style="font-size:10px;"><i class="fas fa-clock"></i> BELUM DIRESPON > 2 JAM</span>';
                                            }
                                        }
                                ?>
                                <tr onclick="window.location='detail_chat.php?id=<?php echo $c['id_chat']; ?>'" style="cursor:pointer">
                                    <td class="pl-4">
                                        <strong><?php echo ($role == 'bpr') ? $c['judul_masalah'] : $c['nama_bpr']; ?></strong>
                                        <?php if($role != 'bpr') echo "<br><small class='text-muted'>".$c['judul_masalah']."</small>"; ?>
                                        <?php echo $notif_sla; ?>
                                    </td>
                                    <td class="small">
                                        <i class="fas fa-user-tie text-muted mr-1"></i> 
                                        <?php echo $c['nama_staf'] ? $c['nama_staf'] : '<span class="text-danger">Belum Ditunjuk</span>'; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-pill badge-<?php echo $badge; ?> px-3 py-2"><?php echo $c['status']; ?></span>
                                    </td>
                                    <td class="text-right small text-muted pr-4">
                                        <?php echo date('d/m H:i', strtotime($c['timestamp'])); ?>
                                    </td>
                                </tr>
                                <?php } } else { echo "<tr><td colspan='4' class='text-center py-5 text-muted'>Belum ada data komplain.</td></tr>"; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- LOG AKTIVITAS -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 12px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-bell mr-2 text-warning"></i> Aktivitas Sistem</h6>
                </div>
                <div class="card-body overflow-auto" style="max-height: 450px;">
                    <ul class="list-group list-group-flush small">
                        <?php 
                        $logs = mysqli_query($conn, "SELECT * FROM activity_log ORDER BY timestamp DESC LIMIT 8");
                        while($l = mysqli_fetch_array($logs)){
                        ?>
                        <li class="list-group-item px-0 border-0 d-flex align-items-start mb-2">
                            <div class="bg-light text-primary p-2 rounded-circle mr-3"><i class="fas <?php echo $l['icon']; ?> fa-fw"></i></div>
                            <div>
                                <div class="font-weight-bold text-dark"><?php echo $l['keterangan']; ?></div>
                                <div class="text-muted small"><?php echo date('H:i', strtotime($l['timestamp'])); ?></div>
                            </div>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- MODAL TAMBAH LAPORAN -->
<div class="modal fade" id="modalLapor" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title font-weight-bold">Buat Laporan Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Judul Masalah</label>
                        <input type="text" name="judul" class="form-control" placeholder="Apa masalah Anda?" required>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold">Lampiran Foto <small class="text-muted">(Opsional)</small></label>
                        <input type="file" name="foto" class="form-control-file border p-2 rounded w-100">
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button name="buat_chat" class="btn btn-primary btn-block py-3 font-weight-bold shadow-sm">KIRIM KOMPLAIN</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
if(isset($_POST['buat_chat'])){
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    
    if($role == 'bpr') {
        $id_bpr = $id_user;
        // Ambil penanggung jawab yang sudah dipilih BPR di pengaturan
        $get_pj = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id_pegawai FROM bpr WHERE id_bpr='$id_bpr'"));
        $id_pj = $get_pj['id_pegawai'];

        if(!$id_pj) {
            echo "<script>alert('Harap pilih Pegawai Penanggung Jawab di menu Pengaturan!'); window.location='pengaturan.php';</script>";
            exit;
        }
    } else {
        // Jika Admin/Lainnya yang lapor, default ke BPR Internal ID 1
        $id_bpr = 1;
        $id_pj = $id_user;
    }

    $nama_foto = "";
    if($_FILES['foto']['name'] != ""){
        $nama_foto = time() . "_" . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $nama_foto);
    }

    $sql = "INSERT INTO chat (id_bpr, id_pegawai, judul_masalah, foto, status) VALUES ('$id_bpr', '$id_pj', '$judul', '$nama_foto', 'C')";
    
    if(mysqli_query($conn, $sql)){
        $msg_log = $_SESSION['nama'] . " membuat laporan baru: " . $judul;
        mysqli_query($conn, "INSERT INTO activity_log (keterangan, icon, warna) VALUES ('$msg_log', 'fa-paper-plane', 'primary')");
        echo "<script>window.location='dashboard.php';</script>";
    }
}
?>
</body>
</html>