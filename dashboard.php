<?php 
include 'config.php'; 
include 'menu.php'; 

// Pastikan sudah login
if(!isset($_SESSION['id_user'])) { header("location:index.php"); }
?>

<div class="container">
    <!-- 1. Jumbotron Selamat Datang -->
    <div class="card bg-white shadow-sm border-0 mb-4 text-center text-md-left">
        <div class="card-body py-4">
            <h1 class="font-weight-bold text-dark">Selamat Datang, <?php echo $_SESSION['nama']; ?>!</h1>
            <p class="text-muted lead">Sistem Helpdesk Pelayanan - CV. Royalma.</p>
            <div class="badge badge-info p-2 text-uppercase px-3">Divisi: <?php echo $_SESSION['role']; ?></div>
        </div>
    </div>

    <!-- 2. Kotak Statistik (C, O, U, F) -->
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0" style="border-top: 5px solid #6c757d !important;">
                <div class="card-body text-center">
                    <h6 class="text-muted font-weight-bold small">Status C (Create)</h6>
                    <?php $qC = mysqli_query($conn, "SELECT id_chat FROM chat WHERE status='C'"); ?>
                    <h2 class="font-weight-bold"><?php echo mysqli_num_rows($qC); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0" style="border-top: 5px solid #007bff !important;">
                <div class="card-body text-center">
                    <h6 class="text-muted font-weight-bold small">Status O (Open)</h6>
                    <?php $qO = mysqli_query($conn, "SELECT id_chat FROM chat WHERE status='O'"); ?>
                    <h2 class="font-weight-bold text-primary"><?php echo mysqli_num_rows($qO); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0" style="border-top: 5px solid #dc3545 !important;">
                <div class="card-body text-center">
                    <h6 class="text-muted font-weight-bold small">Status U (Unfinish)</h6>
                    <?php $qU = mysqli_query($conn, "SELECT id_chat FROM chat WHERE status='U'"); ?>
                    <h2 class="font-weight-bold text-danger"><?php echo mysqli_num_rows($qU); ?></h2>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card shadow-sm border-0" style="border-top: 5px solid #28a745 !important;">
                <div class="card-body text-center">
                    <h6 class="text-muted font-weight-bold small">Status F (Finish)</h6>
                    <?php $qF = mysqli_query($conn, "SELECT id_chat FROM chat WHERE status='F'"); ?>
                    <h2 class="font-weight-bold text-success"><?php echo mysqli_num_rows($qF); ?></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Tabel Daftar Komplain Terbaru -->
    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list mr-2"></i> Daftar Komplain Terbaru</span>
                    <a href="data_komplain.php" class="btn btn-sm btn-link text-primary p-0 font-weight-bold text-decoration-none">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr class="small text-muted text-uppercase">
                                    <th>BPR Pelapor</th>
                                    <th>Judul Masalah</th>
                                    <th class="text-center">Lampiran</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-right">Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT chat.*, bpr.nama_bpr FROM chat 
                                        JOIN bpr ON chat.id_bpr = bpr.id_bpr 
                                        ORDER BY timestamp DESC LIMIT 5";
                                $res = mysqli_query($conn, $sql);
                                if(mysqli_num_rows($res) > 0){
                                    while($row = mysqli_fetch_array($res)){
                                        $badge = ($row['status']=='C'?'secondary':($row['status']=='O'?'primary':($row['status']=='U'?'danger':'success')));
                                ?>
                                <tr style="cursor: pointer;" onclick="window.location='detail_chat.php?id=<?php echo $row['id_chat']; ?>'">
                                    <td class="font-weight-bold"><?php echo $row['nama_bpr']; ?></td>
                                    <td><?php echo $row['judul_masalah']; ?></td>
                                    <td class="text-center">
                                        <?php if($row['foto'] != ""): ?>
                                            <i class="fas fa-image text-primary" title="Ada Foto"></i>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-<?php echo $badge; ?> px-3 py-2"><?php echo $row['status']; ?></span>
                                    </td>
                                    <td class="text-right small text-muted"><?php echo date('d/m/Y H:i', strtotime($row['timestamp'])); ?></td>
                                </tr>
                                <?php } } else { ?>
                                    <tr><td colspan="5" class="text-center py-4 text-muted small">Belum ada komplain masuk.</td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Form Buat Laporan (Sekarang dengan Upload Foto Opsional) -->
    <div class="card shadow-sm mt-4 border-0 mb-5">
        <div class="card-header bg-primary text-white font-weight-bold">
            <i class="fas fa-plus-circle mr-2"></i> Buat Laporan Komplain Baru
        </div>
        <div class="card-body py-4">
             <form method="POST" enctype="multipart/form-data"> <!-- enctype wajib ada -->
                <div class="form-group">
                    <label class="font-weight-bold">Apa masalah yang anda hadapi?</label>
                    <input type="text" name="judul" class="form-control form-control-lg" placeholder="Contoh: Aplikasi Error saat login..." required>
                </div>
                
                <div class="form-group mt-3">
                    <label class="font-weight-bold">Lampiran Foto <span class="text-muted small font-italic">(Opsional)</span></label>
                    <div class="custom-file">
                        <input type="file" name="foto" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Pilih foto jika ada bukti...</label>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button name="buat_chat" class="btn btn-primary shadow-sm px-5 btn-lg font-weight-bold">Kirim Laporan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php 
if(isset($_POST['buat_chat'])){
    $judul = mysqli_real_escape_string($conn, $_POST['judul']);
    $id_user = $_SESSION['id_user'];
    $role = $_SESSION['role'];

    // LOGIKA PENENTUAN ID BPR
    if($role == 'bpr'){
        // Jika yang login BPR, gunakan ID dia sendiri
        $id_lapor = $id_user;
    } else {
        // Jika yang login Admin/Pegawai/Supervisor, kita cari ID BPR pertama yang tersedia di database
        $cek_bpr = mysqli_query($conn, "SELECT id_bpr FROM bpr LIMIT 1");
        $data_bpr = mysqli_fetch_assoc($cek_bpr);
        
        if($data_bpr){
            $id_lapor = $data_bpr['id_bpr'];
        } else {
            // Jika tabel BPR benar-benar kosong, kita hentikan agar tidak error lagi
            echo "<script>alert('Error: Belum ada data BPR di database. Admin harus menyetujui/memasukkan data BPR terlebih dahulu!'); window.location='dashboard.php';</script>";
            exit;
        }
    }

    // Logika Upload Foto
    $nama_foto = "";
    if($_FILES['foto']['name'] != ""){
        $nama_foto = time() . "_" . $_FILES['foto']['name'];
        $tmp_foto  = $_FILES['foto']['tmp_name'];
        move_uploaded_file($tmp_foto, "uploads/" . $nama_foto);
    }

    // Simpan ke tabel chat
    $sql = "INSERT INTO chat (id_bpr, judul_masalah, foto, status) VALUES ('$id_lapor', '$judul', '$nama_foto', 'C')";
    
    if(mysqli_query($conn, $sql)){
        echo "<script>alert('Komplain Berhasil Dikirim!'); window.location='dashboard.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!-- Script agar nama file muncul saat dipilih -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script>
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
</script>

</body>
</html>