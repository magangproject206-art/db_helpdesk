<?php
include 'config.php';

if(isset($_POST['id_komplain'])){
    $id_komplain = $_POST['id_komplain'];
    $id_admin = $_POST['id_admin'];

    // Update siapa admin yang bertanggung jawab
    $query = mysqli_query($conn, "UPDATE komplain SET id_admin='$id_admin' WHERE id_komplain='$id_komplain'");

    if($query){
        echo "<script>alert('Tugas berhasil dialihkan ke Admin.'); window.location='data_komplain.php';</script>";
    } else {
        echo "Gagal menugaskan admin.";
    }
}
?>