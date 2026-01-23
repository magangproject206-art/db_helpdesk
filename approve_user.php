<?php include 'config.php'; include 'menu.php'; ?>
<div class="container">
    <h3>Persetujuan Member Baru</h3>
    <table class="table table-bordered">
        <tr><th>Nama</th><th>Role</th><th>Aksi</th></tr>
        <?php
        $q = mysqli_query($conn, "SELECT * FROM users WHERE status_akun='pending'");
        while($u = mysqli_fetch_array($q)){
        ?>
        <tr>
            <td><?php echo $u['nama']; ?></td>
            <td><?php echo $u['role']; ?></td>
            <td><a href="approve_user.php?setuju=<?php echo $u['id_user']; ?>" class="btn btn-sm btn-primary">Setujui</a></td>
        </tr>
        <?php } ?>
    </table>
</div>
<?php
if(isset($_GET['setuju'])){
    mysqli_query($conn, "UPDATE users SET status_akun='aktif' WHERE id_user='".$_GET['setuju']."'");
    echo "<script>alert('User Aktif'); window.location='approve_user.php';</script>";
}
?>