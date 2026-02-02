<?php 
include 'config.php'; 
include 'menu.php'; 

if(!isset($_SESSION['id_user'])) { header("location:index.php"); exit(); }

$id_chat = $_GET['id'];
$my_id = $_SESSION['id_user'];
$my_role = $_SESSION['role'];

// Ambil info utama komplain & Penanggung Jawab
$sql_info = "SELECT chat.*, bpr.nama_bpr, pegawai.nama as nama_pj 
             FROM chat 
             JOIN bpr ON chat.id_bpr = bpr.id_bpr 
             LEFT JOIN pegawai ON chat.id_pegawai = pegawai.id_pegawai 
             WHERE chat.id_chat='$id_chat'";
$query_info = mysqli_query($conn, $sql_info);
$info_chat = mysqli_fetch_array($query_info);

// Validasi Keamanan (BPR hanya bisa lihat miliknya)
if($my_role == 'bpr' && $info_chat['id_bpr'] != $my_id) {
    echo "<script>alert('Akses ditolak!'); window.location='dashboard.php';</script>";
    exit();
}

// PROSES KIRIM PESAN (BAGIAN YANG TADI ERROR)
if(isset($_POST['kirim_pesan'])){
    $pesan = mysqli_real_escape_string($conn, $_POST['pesan']);
    
    $nama_foto = "";
    if($_FILES['foto']['name'] != ""){
        $nama_foto = time() . "_" . $_FILES['foto']['name'];
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/" . $nama_foto);
    }

    // Query insert ke kolom yang sudah diperbaiki
    $query_insert = "INSERT INTO detail_chat (id_chat, sender_id, sender_role, pesan, foto_lampiran) 
                     VALUES ('$id_chat', '$my_id', '$my_role', '$pesan', '$nama_foto')";
    if(mysqli_query($conn, $query_insert)){
    echo "<script>window.location='detail_chat.php?id=$id_chat';</script>";
    exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Update Status (Hanya Internal)
if(isset($_POST['update_status'])){
    $status_baru = $_POST['status_baru'];
    mysqli_query($conn, "UPDATE chat SET status='$status_baru' WHERE id_chat='$id_chat'");
    echo "<script>window.location='detail_chat.php?id=$id_chat';</script>";
    exit();
    header("location:detail_chat.php?id=".$id_chat);
}
?>

<div class="container mb-5">
    <div class="row">
        <!-- Ruang Chat -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-primary text-white py-3">
                    <span class="font-weight-bold"><i class="fas fa-comments mr-2"></i> <?php echo $info_chat['judul_masalah']; ?></span>
                </div>
                
                <div class="card-body bg-light" style="height: 450px; overflow-y: auto; display: flex; flex-direction: column-reverse;">
                    <div>
                        <?php 
                        $query_pesan = mysqli_query($conn, "SELECT * FROM detail_chat WHERE id_chat='$id_chat' ORDER BY timestamp ASC");
                        while($p = mysqli_fetch_array($query_pesan)){
                            $is_me = ($p['sender_id'] == $my_id && $p['sender_role'] == $my_role);
                        ?>
                            <div class="d-flex <?php echo $is_me ? 'justify-content-end' : 'justify-content-start'; ?> mb-3">
                                <div class="p-3 shadow-sm <?php echo $is_me ? 'bg-primary text-white' : 'bg-white text-dark'; ?>" 
                                     style="max-width: 80%; border-radius: 15px;">
                                    <small class="font-weight-bold d-block mb-1 <?php echo $is_me ? 'text-warning' : 'text-primary'; ?>">
                                        <?php echo strtoupper($p['sender_role']); ?>
                                    </small>
                                    <p class="mb-0"><?php echo $p['pesan']; ?></p>
                                    <?php if($p['foto_lampiran'] != ""): ?>
                                        <a href="uploads/<?php echo $p['foto_lampiran']; ?>" target="_blank">
                                            <img src="uploads/<?php echo $p['foto_lampiran']; ?>" class="img-fluid rounded mt-2" style="max-height: 200px;">
                                        </a>
                                    <?php endif; ?>
                                    <small class="d-block mt-2 text-right opacity-50" style="font-size: 0.6rem;">
                                        <?php echo date('H:i', strtotime($p['timestamp'])); ?>
                                    </small>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 py-3">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="input-group shadow-sm">
                            <input type="text" name="pesan" class="form-control" placeholder="Tulis balasan..." required>
                            <div class="input-group-append">
                                <label class="btn btn-outline-secondary mb-0" style="cursor:pointer;">
                                    <i class="fas fa-camera"></i> <input type="file" name="foto" hidden>
                                </label>
                                <button name="kirim_pesan" class="btn btn-primary px-4">Kirim</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Status -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-body">
                    <label class="small text-muted mb-0">Pelapor:</label>
                    <p class="font-weight-bold"><?php echo $info_chat['nama_bpr']; ?></p>
                    
                    <label class="small text-muted mb-0">Staf Penanggung Jawab:</label>
                    <p class="font-weight-bold text-primary"><?php echo $info_chat['nama_pj'] ?? 'Belum Ditunjuk'; ?></p>
                    
                    <hr>
                    <label class="small text-muted">Status Progres:</label>
                    <?php 
                        $st = $info_chat['status'];
                        $badge = ($st=='C'?'secondary':($st=='O'?'primary':($st=='U'?'danger':'success')));
                    ?>
                    <h4 class="badge badge-<?php echo $badge; ?> d-block py-2"><?php echo $st; ?></h4>
                    
                    <?php if($my_role != 'bpr'): ?>
                    <form method="POST" class="mt-4">
                        <select name="status_baru" class="form-control mb-2">
                            <option value="C" <?php if($st=='C') echo 'selected'; ?>>C (Create)</option>
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