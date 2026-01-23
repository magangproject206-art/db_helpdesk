<?php
session_start();
session_destroy(); // Menghapus semua data login
header("location:index.php"); // Kembali ke halaman login
?>