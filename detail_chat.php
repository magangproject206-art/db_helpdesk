<?php 
include 'config.php'; 
include 'menu.php'; 

if(!isset($_SESSION['id_user'])) { header("location:index.php"); }

$id_chat = $_GET['id'];

// Ambil info utama komplain
$info_chat = mysqli_fetch_array(mysqli_query($conn, "SELECT chat.*, bpr.nama_bpr FROM chat JOIN bpr ON chat.id_bpr = bpr.id_bpr WHERE id_chat='$id_chat'"));

// Proses Kirim Pesan Baru
if(isset($_POST['kirim_pesan'])){
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    $sender_id = $_SESSION['id_user'];
    $sender_role = $_SESSION['role'];

    $nama_foto = "";
    if($_FILES['foto']['name'] != ""){
        $nama_foto = time() . "_" . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $nama_foto);
    }

    mysqli_query($conn, "INSERT INTO detail_chat (id_chat, sender_id, sender_role, pesan, foto_lampiran) 
                        VALUES ('$id_chat', '$sender_id', '$sender_role', '$pesan', '$nama_foto')");
    header("location:detail_chat.php?id=".$id_chat);
}

// Proses Update Status (Hanya Admin/Supervisor)
if(isset($_POST['update_status'])){
    $status_baru = $_POST['status_baru'];
    mysqli_query($conn, "UPDATE chat SET status='$status_baru' WHERE id_chat='$id_chat'");
    header("location:detail_chat.php?id=".$id_chat);
}
?>

<div class="container mb-5">
    <div class="row">
        <!-- Bagian Chat (Kiri) -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <span class="font-weight-bold">Ruang Diskusi: <?php echo $info_chat['judul_masalah']; ?></span>
                    <a href="dashboard.php" class="text-white small mt-1">Kembali</a>
                </div>
                
                <!-- Area Pesan Chat -->
                <div class="card-body bg-light" style="height: 500px; overflow-y: auto; display: flex; flex-direction: column-reverse;">
                    <div>
                        <?php 
                        $query_pesan = mysqli_query($conn, "SELECT * FROM detail_chat WHERE id_chat='$id_chat' ORDER BY timestamp ASC");
                        while($p = mysqli_fetch_array($query_pesan)){
                            // Cek apakah ini pesan saya atau orang lain
                            $is_me = ($p['sender_id'] == $_SESSION['id_user'] && $p['sender_role'] == $_SESSION['role']);
                        ?>
                            <div class="d-flex <?php echo $is_me ? 'justify-content-end' : 'justify-content-start'; ?> mb-3">
                                <div class="p-3 rounded shadow-sm <?php echo $is_me ? 'bg-primary text-white' : 'bg-white text-dark'; ?>" style="max-width: 80%;">
                                    <small class="font-weight-bold d-block mb-1 <?php echo $is_me ? 'text-warning' : 'text-primary'; ?>">
                                        <?php echo strtoupper($p['sender_role']); ?>
                                    </small>
                                    <?php echo $p['pesan']; ?>
                                    
                                    <?php if($p['foto_lampiran'] != ""): ?>
                                        <div class="mt-2 text-center">
                                            <a href="uploads/<?php echo $p['foto_lampiran']; ?>" target="_blank">
                                                <img src="uploads/<?php echo $p['foto_lampiran']; ?>" class="img-fluid rounded border" style="max-height: 200px;">
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <small class="d-block mt-2 text-right <?php echo $is_me ? 'text-white-50' : 'text-muted'; ?>" style="font-size: 0.7rem;">
                                        <?php echo date('H:i', strtotime($p['timestamp'])); ?>
                                    </small>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <!-- Input Balasan -->
                <div class="card-footer bg-white border-0">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="input-group">
                            <input type="text" name="pesan" class="form-control" placeholder="Tulis pesan anda..." required>
                            <div class="input-group-append">
                                <label class="btn btn-outline-secondary mb-0" style="cursor: pointer;">
                                    <i class="fas fa-camera"></i> <input type="file" name="foto" hidden>
                                </label>
                                <button name="kirim_pesan" class="btn btn-primary px-4">Kirim</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Panel Kontrol Status (Kanan) -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-dark text-white font-weight-bold">Info & Status</div>
                <div class="card-body">
                    <p class="small text-muted mb-1">Pelapor:</p>
                    <p class="font-weight-bold"><?php echo $info_chat['nama_bpr']; ?></p>
                    <hr>
                    <p class="small text-muted mb-1">Status Progres:</p>
                    <?php 
                        $st = $info_chat['status'];
                        $badge = ($st=='C'?'secondary':($st=='O'?'primary':($st=='U'?'danger':'success')));
                    ?>
                    <h3 class="badge badge-<?php echo $badge; ?> px-4 py-2 text-uppercase d-block"><?php echo $st; ?></h3>
                    
                    <?php if($_SESSION['role'] != 'bpr'): ?>
                    <hr>
                    <form method="POST">
                        <label class="small font-weight-bold">Ubah Status Komplain:</label>
                        <select name="status_baru" class="form-control mb-2">
                            <option value="C" <?php if($st=='C') echo 'selected'; ?>>C (Create)</option>
                            <option value="O" <?php if($st=='O') echo 'selected'; ?>>O (Open)</option>
                            <option value="U" <?php if($st=='U') echo 'selected'; ?>>U (Unfinish)</option>
                            <option value="F" <?php if($st=='F') echo 'selected'; ?>>F (Finish)</option>
                        </select>
                        <button name="update_status" class="btn btn-danger btn-block font-weight-bold">Update Status</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>