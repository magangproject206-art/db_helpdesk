<?php 
include 'config.php'; 
include 'menu.php'; 

$id = $_SESSION['id_user'];
$role = $_SESSION['role'];

if($role == 'bpr') {
    $user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM bpr WHERE id_bpr='$id'"));
    $nama_user = $user_data['nama_bpr'];
} else {
    $user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM pegawai WHERE id_pegawai='$id'"));
    $nama_user = $user_data['nama'];
}

// Proses Update
if(isset($_POST['update_profil'])){
    $nama_baru = mysqli_real_escape_string($conn, $_POST['nama']);
    $pass_baru = mysqli_real_escape_string($conn, $_POST['password']);
    
    if($role == 'bpr') {
        $pegawai_pilihan = $_POST['id_pegawai'];
        mysqli_query($conn, "UPDATE bpr SET nama_bpr='$nama_baru', password='$pass_baru', id_pegawai='$pegawai_pilihan' WHERE id_bpr='$id'");
    } else {
        mysqli_query($conn, "UPDATE pegawai SET nama='$nama_baru', password='$pass_baru' WHERE id_pegawai='$id'");
    }
    echo "<script>alert('Perubahan disimpan!'); window.location='pengaturan.php';</script>";
}
?>

<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white font-weight-bold">Pengaturan Akun & Layanan</div>
                <div class="card-body">
                    <form method="POST">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control" value="<?php echo $nama_user; ?>" required>
                        </div>
                        
                        <?php if($role == 'bpr'): ?>
                        <div class="form-group">
                            <label class="text-primary font-weight-bold">Pilih Pegawai Penanggung Jawab:</label>
                            <select name="id_pegawai" class="form-control" required>
                                <option value="">-- Pilih Pegawai --</option>
                                <?php 
                                $peg = mysqli_query($conn, "SELECT id_pegawai, nama FROM pegawai WHERE role='pegawai' OR role='admin'");
                                while($p = mysqli_fetch_array($peg)){
                                    $sel = ($p['id_pegawai'] == $user_data['id_pegawai']) ? 'selected' : '';
                                    echo "<option value='".$p['id_pegawai']."' $sel>".$p['nama']."</option>";
                                }
                                ?>
                            </select>
                            <small class="text-muted">Pegawai ini yang akan muncul secara otomatis saat Anda membuat komplain baru.</small>
                        </div>
                        <?php endif; ?>

                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" value="<?php echo $user_data['password']; ?>" required>
                        </div>
                        <button name="update_profil" class="btn btn-primary btn-block">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>