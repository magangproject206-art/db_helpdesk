<?php include 'config.php'; include 'menu.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">Buat Laporan Masalah</div>
        <div class="card-body">
            <form method="POST" enctype="multipart/form-data">
                <label>Judul Masalah</label>
                <input type="text" name="judul" class="form-control mb-2" required>
                <label>Deskripsi Masalah</label>
                <textarea name="deskripsi" class="form-control mb-2" rows="4"></textarea>
                <label>Foto Bukti (Boleh Kosong)</label>
                <input type="file" name="foto" class="form-control mb-3">
                <button type="submit" name="simpan" class="btn btn-primary">Kirim Laporan</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>

<?php
if(isset($_POST['simpan'])){
    $id_user = $_SESSION['id_user']; $judul = $_POST['judul']; $desc = $_POST['deskripsi'];
    $foto = $_FILES['foto']['name'];
    if($foto != "") {
        move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/".$foto);
    }
    $q = mysqli_query($conn, "INSERT INTO komplain (id_user, judul, deskripsi, foto) VALUES ('$id_user', '$judul', '$desc', '$foto')");
    echo "<script>alert('Laporan berhasil dikirim'); window.location='data_komplain.php';</script>";
}
?>