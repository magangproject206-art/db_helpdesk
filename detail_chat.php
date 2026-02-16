<?php 
include 'config.php'; 
include 'menu.php'; 

if(!isset($_SESSION['id_user'])) { echo "<script>window.location='index.php';</script>"; exit(); }

$id_chat = $_GET['id'];
$my_id = $_SESSION['id_user'];
$my_role = $_SESSION['role'];

// 1. QUERY INFO KOMPLAIN & PJ
$sql_info = "SELECT chat.*, bpr.nama_bpr, pegawai.nama as nama_pj 
             FROM chat 
             JOIN bpr ON chat.id_bpr = bpr.id_bpr 
             LEFT JOIN pegawai ON chat.id_pegawai = pegawai.id_pegawai 
             WHERE chat.id_chat='$id_chat'";
$query_info = mysqli_query($conn, $sql_info);
$info_chat = mysqli_fetch_array($query_info);

// VALIDASI KEAMANAN: BPR dilarang intip milik orang lain
if($my_role == 'bpr' && $info_chat['id_bpr'] != $my_id) {
    echo "<script>alert('Akses Ditolak!'); window.location='dashboard.php';</script>";
    exit();
}

// 2. LOGIKA KIRIM PESAN & FOTO
if(isset($_POST['kirim_pesan'])){
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    $nama_foto = "";

    // Cek apakah ada file foto yang dipilih
    if(isset($_FILES['foto']) && $_FILES['foto']['name'] != ""){
        $target_dir = "uploads/";
        $nama_asli = $_FILES['foto']['name'];
        $ekstensi = strtolower(pathinfo($nama_asli, PATHINFO_EXTENSION));
        
        // Beri nama unik agar tidak tertimpa
        $nama_foto = time() . "_" . basename($nama_asli);
        $target_file = $target_dir . $nama_foto;

        // Validasi format
        $format_diizinkan = array("jpg", "png", "jpeg", "gif");
        if(in_array($ekstensi, $format_diizinkan)){
            move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
        }
    }

    // Simpan ke database
    if($pesan != "" || $nama_foto != ""){
        $query_insert = "INSERT INTO detail_chat (id_chat, sender_id, sender_role, pesan, foto_lampiran) 
                         VALUES ('$id_chat', '$my_id', '$my_role', '$pesan', '$nama_foto')";
        mysqli_query($conn, $query_insert);
    }
    
    // Redirect menggunakan JS untuk menghindari error "headers already sent"
    echo "<script>window.location='detail_chat.php?id=$id_chat';</script>";
    exit();
}

// 3. LOGIKA UPDATE STATUS (Hanya Internal)
if(isset($_POST['update_status'])){
    $status_baru = $_POST['status_baru'];
    mysqli_query($conn, "UPDATE chat SET status='$status_baru' WHERE id_chat='$id_chat'");
    
    // Log Aktivitas
    $log_msg = $_SESSION['nama'] . " merubah status komplain #$id_chat ke $status_baru";
    mysqli_query($conn, "INSERT INTO activity_log (keterangan, icon, warna) VALUES ('$log_msg', 'fa-sync', 'info')");
    
    echo "<script>window.location='detail_chat.php?id=$id_chat';</script>";
    exit();
}
?>

<style>
/* CSS RUANG CHAT */
.chat-area { height: 500px; overflow-y: auto; background-color: #e5ddd5; padding: 20px; border-radius: 10px; background-image: url('https://user-images.githubusercontent.com/15075759/28719144-86dc0f70-73b1-11e7-911d-60d70fcded21.png'); }
.bubble { max-width: 75%; padding: 10px 15px; border-radius: 15px; margin-bottom: 10px; position: relative; box-shadow: 0 1px 2px rgba(0,0,0,0.1); }
.bubble-me { background-color: #dcf8c6; margin-left: auto; border-bottom-right-radius: 2px; }
.bubble-other { background-color: #ffffff; margin-right: auto; border-bottom-left-radius: 2px; }
.img-chat { max-width: 100%; border-radius: 10px; margin-top: 8px; border: 1px solid #ddd; }

/* CSS TRACKING PROGRES */
.tracking-list { position: relative; padding-left: 20px; }
.tracking-item { position: relative; padding-bottom: 35px; display: flex; align-items: flex-start; }
.tracking-item:last-child { padding-bottom: 0; }
.tracking-item::before { content: ""; position: absolute; left: 14px; top: 30px; bottom: 0; width: 2px; background: #e9ecef; }
.tracking-item.active::before { background: #007bff; }
.t-icon { width: 32px; height: 32px; border-radius: 50%; background: #dee2e6; color: white; display: flex; align-items: center; justify-content: center; z-index: 2; margin-right: 15px; }
.tracking-item.active .t-icon { background: #007bff; }
.tracking-item.done .t-icon { background: #28a745; }
.tracking-item.warning .t-icon { background: #dc3545; animation: pulse-red 2s infinite; }
@keyframes pulse-red { 0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); } 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); } }
</style>

<div class="container-fluid px-4 mb-5">
    <div class="row">
        <!-- KOLOM KIRI: CHAT -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-primary text-white py-3 d-flex justify-content-between">
                    <span class="font-weight-bold"><i class="fas fa-comments mr-2"></i> <?php echo $info_chat['judul_masalah']; ?></span>
                    <a href="dashboard.php" class="text-white small font-weight-bold">KEMBALI</a>
                </div>
                
                <div class="card-body chat-area d-flex flex-column-reverse">
                    <div>
                        <?php 
                        $query_pesan = mysqli_query($conn, "SELECT * FROM detail_chat WHERE id_chat='$id_chat' ORDER BY timestamp ASC");
                        while($p = mysqli_fetch_array($query_pesan)){
                            $is_me = ($p['sender_id'] == $my_id && $p['sender_role'] == $my_role);
                        ?>
                            <div class="bubble <?php echo $is_me ? 'bubble-me' : 'bubble-other'; ?>">
                                <small class="font-weight-bold d-block text-uppercase" style="font-size: 10px; color: <?php echo $is_me ? '#28a745' : '#007bff'; ?>;">
                                    <?php echo $p['sender_role']; ?>
                                </small>
                                
                                <?php echo $p['pesan']; ?>

                                <?php if($p['foto_lampiran'] != ""): ?>
                                    <a href="uploads/<?php echo $p['foto_lampiran']; ?>" target="_blank" class="d-block mt-1">
                                        <img src="uploads/<?php echo $p['foto_lampiran']; ?>" class="img-chat">
                                    </a>
                                <?php endif; ?>

                                <div class="text-right mt-1">
                                    <small class="text-muted" style="font-size: 9px;"><?php echo date('H:i', strtotime($p['timestamp'])); ?></small>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 py-3">
                    <form method="POST" enctype="multipart/form-data"> <!-- WAJIB ADA ENCTYPE -->
                        <div class="input-group shadow-sm">
                            <input type="text" name="pesan" class="form-control border-0 bg-light" placeholder="Tulis balasan...">
                            <div class="input-group-append">
                                <label class="btn btn-light mb-0 border-0" style="cursor:pointer;" title="Kirim Foto">
                                    <i class="fas fa-camera text-muted"></i> 
                                    <input type="file" name="foto" id="inputFoto" hidden>
                                </label>
                                <button name="kirim_pesan" class="btn btn-primary px-4 font-weight-bold">KIRIM</button>
                            </div>
                        </div>
                        <small id="namaFile" class="text-primary mt-1 d-block"></small>
                    </form>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: TRACKING -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-dark text-white font-weight-bold py-3">Tracking Pelayanan</div>
                <div class="card-body">
                    <div class="mb-3 small">
                        <label class="text-muted mb-0">Pelapor:</label>
                        <p class="font-weight-bold mb-2"><?php echo $info_chat['nama_bpr']; ?></p>
                        <label class="text-muted mb-0">Penanggung Jawab:</label>
                        <p class="font-weight-bold text-primary"><?php echo $info_chat['nama_pj'] ?? 'Mencari Petugas...'; ?></p>
                    </div>
                    <hr>

                    <?php 
                        $st = $info_chat['status'];
                        $step = ($st=='O')?2:(($st=='U')?3:(($st=='F')?4:1));
                    ?>
                    <div class="tracking-list mt-4">
                        <div class="tracking-item <?php echo ($step >= 1) ? 'active done' : ''; ?>">
                            <div class="t-icon"><i class="fas fa-file-import"></i></div>
                            <div class="tracking-content"><span class="font-weight-bold small">CREATED</span></div>
                        </div>
                        <div class="tracking-item <?php echo ($step >= 2) ? 'active ' . ($step > 2 ? 'done' : '') : ''; ?>">
                            <div class="t-icon"><i class="fas fa-user-clock"></i></div>
                            <div class="tracking-content"><span class="font-weight-bold small">OPEN</span></div>
                        </div>
                        <div class="tracking-item <?php echo ($st == 'U') ? 'active warning' : ($step > 3 ? 'done' : ''); ?>" style="<?php echo ($st != 'U' && $step < 4) ? 'opacity:0.3' : ''; ?>">
                            <div class="t-icon"><i class="fas fa-exclamation-triangle"></i></div>
                            <div class="tracking-content"><span class="font-weight-bold small">UNFINISH</span></div>
                        </div>
                        <div class="tracking-item <?php echo ($step == 4) ? 'active done' : ''; ?>">
                            <div class="t-icon"><i class="fas fa-check-double"></i></div>
                            <div class="tracking-content"><span class="font-weight-bold small">FINISH</span></div>
                        </div>
                    </div>

                    <?php if($my_role != 'bpr'): ?>
                    <hr class="mt-4">
                    <form method="POST">
                        <label class="small font-weight-bold">UPDATE STATUS:</label>
                        <select name="status_baru" class="form-control mb-2 shadow-sm border-primary">
                            <option value="C" <?php if($st=='C') echo 'selected'; ?>>C (Create)</option>
                            <option value="O" <?php if($st=='O') echo 'selected'; ?>>O (Open)</option>
                            <option value="U" <?php if($st=='U') echo 'selected'; ?>>U (Unfinish)</option>
                            <option value="F" <?php if($st=='F') echo 'selected'; ?>>F (Finish)</option>
                        </select>
                        <button name="update_status" class="btn btn-primary btn-block font-weight-bold shadow-sm">SIMPAN</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Menampilkan nama file saat dipilih
document.getElementById('inputFoto').onchange = function () {
    document.getElementById('namaFile').innerHTML = "<i class='fas fa-paperclip'></i> Lampiran: " + this.files[0].name;
};
</script>