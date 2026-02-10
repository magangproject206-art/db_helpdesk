<?php 
include 'config.php'; 
include 'menu.php'; 

// Pengaman: Hanya Admin IT yang boleh buka halaman ini
if($_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses Ditolak! Hanya Admin yang boleh mengakses menu ini.'); window.location='dashboard.php';</script>";
    exit();
}

// --- LOGIKA PERSETUJUAN BPR ---
if(isset($_GET['setuju_bpr'])){
    $id = $_GET['setuju_bpr'];
    mysqli_query($conn, "UPDATE bpr SET status_akun='aktif' WHERE id_bpr='$id'");
    echo "<script>window.location='approve_user.php';</script>";
}

// --- LOGIKA PERSETUJUAN STAF/PEGAWAI ---
if(isset($_GET['setuju_pegawai'])){
    $id = $_GET['setuju_pegawai'];
    mysqli_query($conn, "UPDATE pegawai SET status_akun='aktif' WHERE id_pegawai='$id'");
    echo "<script>window.location='approve_user.php';</script>";
}

// --- LOGIKA HAPUS (REJECT) ---
if(isset($_GET['hapus_bpr'])){
    $id = $_GET['hapus_bpr'];
    mysqli_query($conn, "DELETE FROM bpr WHERE id_bpr='$id'");
    echo "<script>window.location='approve_user.php';</script>";
}
if(isset($_GET['hapus_pegawai'])){
    $id = $_GET['hapus_pegawai'];
    mysqli_query($conn, "DELETE FROM pegawai WHERE id_pegawai='$id'");
    echo "<script>window.location='approve_user.php';</script>";
}
?>

<div class="container-fluid px-4 mb-5">
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
        <div class="card-body p-4">
            <h4 class="font-weight-bold mb-1"><i class="fas fa-user-check mr-2 text-success"></i> Manajemen Persetujuan Akun</h4>
            <p class="text-muted small mb-0">Halaman ini digunakan untuk mengaktifkan akun BPR atau Staf yang baru mendaftar.</p>
        </div>
    </div>

    <div class="row">
        <!-- TABEL PENDAFTAR BPR -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 15px;">
                <div class="card-header bg-white font-weight-bold py-3">Pendaftar Mitra BPR (Nasabah)</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted">
                                <th class="pl-4">Nama BPR</th>
                                <th>Username</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $q_bpr = mysqli_query($conn, "SELECT * FROM bpr WHERE status_akun='pending'");
                            if(mysqli_num_rows($q_bpr) > 0){
                                while($b = mysqli_fetch_array($q_bpr)){
                            ?>
                            <tr>
                                <td class="pl-4 font-weight-bold text-dark"><?php echo $b['nama_bpr']; ?></td>
                                <td><?php echo $b['username']; ?></td>
                                <td class="text-center">
                                    <a href="approve_user.php?setuju_bpr=<?php echo $b['id_bpr']; ?>" class="btn btn-sm btn-success shadow-sm" onclick="return confirm('Aktifkan akun ini?')"><i class="fas fa-check"></i></a>
                                    <a href="approve_user.php?hapus_bpr=<?php echo $b['id_bpr']; ?>" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Tolak & Hapus pendaftaran ini?')"><i class="fas fa-times"></i></a>
                                </td>
                            </tr>
                            <?php } } else { echo "<tr><td colspan='3' class='text-center py-4 text-muted small'>Tidak ada antrean BPR.</td></tr>"; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- TABEL PENDAFTAR STAF INTERNAL -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 15px;">
                <div class="card-header bg-white font-weight-bold py-3">Pendaftar Staf Internal (Pegawai/Supervisor)</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr class="small text-muted">
                                <th class="pl-4">Nama Staf</th>
                                <th>Role</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $q_staf = mysqli_query($conn, "SELECT * FROM pegawai WHERE status_akun='pending' AND role != 'admin'");
                            if(mysqli_num_rows($q_staf) > 0){
                                while($s = mysqli_fetch_array($q_staf)){
                            ?>
                            <tr>
                                <td class="pl-4 font-weight-bold text-dark"><?php echo $s['nama']; ?></td>
                                <td><span class="badge badge-info"><?php echo strtoupper($s['role']); ?></span></td>
                                <td class="text-center">
                                    <a href="approve_user.php?setuju_pegawai=<?php echo $s['id_pegawai']; ?>" class="btn btn-sm btn-success shadow-sm" onclick="return confirm('Aktifkan akun staf ini?')"><i class="fas fa-check"></i></a>
                                    <a href="approve_user.php?hapus_pegawai=<?php echo $s['id_pegawai']; ?>" class="btn btn-sm btn-danger shadow-sm" onclick="return confirm('Hapus staf ini?')"><i class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                            <?php } } else { echo "<tr><td colspan='3' class='text-center py-4 text-muted small'>Tidak ada antrean staf.</td></tr>"; } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>