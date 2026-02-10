<?php 
include 'config.php'; 
include 'menu.php'; 

if(!isset($_SESSION['id_user'])) { echo "<script>window.location='index.php';</script>"; exit(); }

$id_chat = $_GET['id'];
$my_id = $_SESSION['id_user'];
$my_role = $_SESSION['role'];

// 1. QUERY INFO KOMPLAIN
$sql_info = "SELECT chat.*, bpr.nama_bpr, pegawai.nama as nama_pj 
             FROM chat 
             JOIN bpr ON chat.id_bpr = bpr.id_bpr 
             LEFT JOIN pegawai ON chat.id_pegawai = pegawai.id_pegawai 
             WHERE chat.id_chat='$id_chat'";
$query_info = mysqli_query($conn, $sql_info);
$info_chat = mysqli_fetch_array($query_info);

// VALIDASI KEAMANAN
if($my_role == 'bpr' && $info_chat['id_bpr'] != $my_id) {
    echo "<script>alert('Akses Ditolak!'); window.location='dashboard.php';</script>";
    exit();
}

// LOGIKA KIRIM PESAN & UPDATE STATUS (Gunakan JavaScript Redirect agar tidak error header)
if(isset($_POST['kirim_pesan'])){
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    $nama_foto = "";
    if(isset($_FILES['foto']) && $_FILES['foto']['name'] != ""){
        $nama_foto = time() . "_" . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $nama_foto);
    }
    mysqli_query($conn, "INSERT INTO detail_chat (id_chat, sender_id, sender_role, pesan, foto_lampiran) 
                        VALUES ('$id_chat', '$my_id', '$my_role', '$pesan', '$nama_foto')");
    echo "<script>window.location='detail_chat.php?id=$id_chat';</script>";
    exit();
}

if(isset($_POST['update_status'])){
    $status_baru = $_POST['status_baru'];
    mysqli_query($conn, "UPDATE chat SET status='$status_baru' WHERE id_chat='$id_chat'");
    echo "<script>window.location='detail_chat.php?id=$id_chat';</script>";
    exit();
}
?>

<style>
/* CSS agar tampilan pas di layar (Samping ke Samping) */
.chat-container { height: calc(100vh - 200px); min-height: 500px; }
.chat-area { height: 80%; overflow-y: auto; background-color: #e5ddd5; padding: 20px; border-radius: 10px; }
.bubble { max-width: 80%; padding: 10px 15px; border-radius: 15px; margin-bottom: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
.bubble-me { background-color: #dcf8c6; margin-left: auto; border-bottom-right-radius: 2px; }
.bubble-other { background-color: #ffffff; margin-right: auto; border-bottom-left-radius: 2px; }

/* CSS Tracking List agar Ringkas */
.tracking-list { position: relative; padding-left: 20px; font-size: 0.85rem; }
.tracking-item { position: relative; padding-bottom: 25px; display: flex; align-items: center; }
.tracking-item::before { content: ""; position: absolute; left: 14px; top: 25px; bottom: 0; width: 2px; background: #e9ecef; }
.tracking-item:last-child::before { display: none; }
.t-icon { width: 28px; height: 28px; border-radius: 50%; background: #dee2e6; color: white; display: flex; align-items: center; justify-content: center; z-index: 2; margin-right: 15px; }
.tracking-item.active .t-icon { background: #007bff; }
.tracking-item.done .t-icon { background: #28a745; }
.tracking-item.warning .t-icon { background: #dc3545; animation: pulse 2s infinite; }
@keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.4); } 70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); } 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); } }
</style>

<div class="container-fluid px-4">
    <div class="row">
        <!-- KOLOM KIRI (70% Lebar): RUANG CHAT -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 chat-container" style="border-radius: 15px;">
                <div class="card-header bg-primary text-white py-3">
                    <span class="font-weight-bold"><i class="fas fa-comments mr-2"></i> <?php echo $info_chat['judul_masalah']; ?></span>
                </div>
                
                <div class="card-body chat-area d-flex flex-column-reverse">
                    <div>
                        <?php 
                        $query_pesan = mysqli_query($conn, "SELECT * FROM detail_chat WHERE id_chat='$id_chat' ORDER BY timestamp ASC");
                        while($p = mysqli_fetch_array($query_pesan)){
                            $is_me = ($p['sender_id'] == $my_id && $p['sender_role'] == $my_role);
                        ?>
                            <div class="bubble <?php echo $is_me ? 'bubble-me' : 'bubble-other'; ?>">
                                <small class="font-weight-bold d-block text-uppercase" style="font-size: 10px;"><?php echo $p['sender_role']; ?></small>
                                <?php echo $p['pesan']; ?>
                                <div class="text-right"><small class="text-muted" style="font-size: 9px;"><?php echo date('H:i', strtotime($p['timestamp'])); ?></small></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 py-3">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="input-group shadow-sm">
                            <input type="text" name="pesan" class="form-control" placeholder="Tulis pesan..." required>
                            <div class="input-group-append">
                                <label class="btn btn-light mb-0" style="cursor:pointer;"><i class="fas fa-camera"></i><input type="file" name="foto" hidden></label>
                                <button name="kirim_pesan" class="btn btn-primary px-4 font-weight-bold">KIRIM</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN (30% Lebar): TRACKING STATUS -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-dark text-white font-weight-bold py-3">Progres Pelayanan</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="small text-muted mb-0">Pelapor:</label>
                        <p class="font-weight-bold mb-2"><?php echo $info_chat['nama_bpr']; ?></p>
                        <label class="small text-muted mb-0">Penanggung Jawab:</label>
                        <p class="font-weight-bold text-primary mb-0"><?php echo $info_chat['nama_pj'] ?? 'Mencari Petugas...'; ?></p>
                    </div>
                    <hr>

                    <!-- TRACKING C-O-U-F -->
                    <?php 
                        $st = $info_chat['status'];
                        $step = ($st=='O')?2:(($st=='U')?3:(($st=='F')?4:1));
                    ?>
                    <div class="tracking-list mt-3">
                        <div class="tracking-item <?php echo ($step >= 1) ? 'done' : ''; ?>">
                            <div class="t-icon"><i class="fas fa-file-import"></i></div>
                            <div><span class="font-weight-bold d-block">Created</span><small class="text-muted">Laporan Diterima</small></div>
                        </div>
                        <div class="tracking-item <?php echo ($step >= 2) ? ($step > 2 ? 'done' : 'active') : ''; ?>">
                            <div class="t-icon"><i class="fas fa-user-clock"></i></div>
                            <div><span class="font-weight-bold d-block">Open</span><small class="text-muted">Sedang Diproses</small></div>
                        </div>
                        <div class="tracking-item <?php echo ($st == 'U') ? 'warning' : ($step > 3 ? 'done' : ''); ?>">
                            <div class="t-icon"><i class="fas fa-exclamation-triangle"></i></div>
                            <div><span class="font-weight-bold d-block">Unfinish</span><small class="text-muted">Ada Kendala</small></div>
                        </div>
                        <div class="tracking-item <?php echo ($step == 4) ? 'done' : ''; ?>">
                            <div class="t-icon"><i class="fas fa-check-double"></i></div>
                            <div><span class="font-weight-bold d-block">Finish</span><small class="text-muted">Selesai</small></div>
                        </div>
                    </div>

                    <?php if($my_role != 'bpr'): ?>
                    <hr>
                    <form method="POST">
                        <select name="status_baru" class="form-control mb-2">
                            <option value="C" <?php if($st=='C') echo 'selected'; ?>>C (Created)</option>
                            <option value="O" <?php if($st=='O') echo 'selected'; ?>>O (Open)</option>
                            <option value="U" <?php if($st=='U') echo 'selected'; ?>>U (Unfinish)</option>
                            <option value="F" <?php if($st=='F') echo 'selected'; ?>>F (Finish)</option>
                        </select>
                        <button name="update_status" class="btn btn-danger btn-block font-weight-bold">UPDATE STATUS</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>