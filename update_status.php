<?php
include 'config.php';

if(isset($_POST['id_komplain'])){
    $id = $_POST['id_komplain'];
    $status = $_POST['status_baru'];

    $query = mysqli_query($conn, "UPDATE komplain SET status_komplain='$status' WHERE id_komplain='$id'");

    if($query){
        header("location:data_komplain.php");
    } else {
        echo "Gagal update status";
    }
}
?>