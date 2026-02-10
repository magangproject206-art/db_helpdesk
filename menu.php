<?php 
if (session_status() === PHP_SESSION_NONE) { session_start(); } 

// Pengaman: Jika tidak ada session, tendang balik ke login
if(!isset($_SESSION['id_user'])) {
    header("location:index.php");
    exit();
}

// Logika untuk mengetahui halaman mana yang sedang dibuka (untuk menu aktif)
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PINtech E-Helpdesk</title>
    <!-- Bootstrap 4 & Font Awesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        :root { --sidebar-width: 260px; --primary-color: #3498db; --dark-bg: #2c3e50; }
        body { background-color: #f4f7f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; overflow-x: hidden; }
        
        /* Sidebar Styling */
        .sidebar { width: var(--sidebar-width); position: fixed; height: 100vh; background: var(--dark-bg); color: white; transition: 0.3s; z-index: 1000; }
        
        /* MODIFIKASI: Styling area Logo */
        .sidebar .brand-section { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); background: #fff; }
        
        .sidebar a { color: rgba(255,255,255,0.6); padding: 15px 25px; display: block; text-decoration: none; transition: 0.2s; font-size: 0.95rem; }
        .sidebar a:hover { color: white; background: rgba(255,255,255,0.05); text-decoration: none; }
        .sidebar a.active { background: #1a252f; color: white; border-left: 5px solid var(--primary-color); font-weight: bold; }
        
        /* Main Content Styling */
        .main-content { margin-left: var(--sidebar-width); padding: 25px; min-height: 100vh; }
        
        /* Top Navbar Styling */
        .top-navbar { background: white; padding: 12px 30px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 30px; border-radius: 15px; }
        .user-avatar { width: 40px; height: 40px; object-fit: cover; border: 2px solid var(--primary-color); padding: 2px; }
        
        /* Dropdown Styling */
        .dropdown-menu { border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 12px; margin-top: 10px; }
        
        @media (max-width: 768px) {
            .sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
            .main-content { margin-left: 0; }
            .sidebar.active { margin-left: 0; }
        }
    </style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar shadow">
    <div class="brand-section">
        <!-- PERUBAHAN DISINI: Memasukkan Logo PINtech -->
        <img src="assets/img/logo.png" alt="Logo PINtech" class="img-fluid" style="max-height: 50px;">
        <div class="small text-dark font-weight-bold mt-2" style="letter-spacing: 1px; font-size: 10px;">E-HELPDESK SYSTEM</div>
    </div>
    
    <div class="mt-4">
        <a href="dashboard.php" class="<?php echo ($current_page == 'dashboard.php') ? 'active' : ''; ?>">
            <i class="fas fa-home mr-3"></i> Dashboard
        </a>
        <a href="data_komplain.php" class="<?php echo ($current_page == 'data_komplain.php' || $current_page == 'detail_chat.php') ? 'active' : ''; ?>">
            <i class="fas fa-ticket-alt mr-3"></i> Laporan Komplain
        </a>
        <a href="analisis_data.php" class="<?php echo ($current_page == 'analisis_data.php') ? 'active' : ''; ?>">
            <i class="fas fa-chart-line mr-3"></i> Analisis Data
        </a>
        
        <?php if($_SESSION['role'] == 'admin'): ?>
            <a href="approve_user.php" class="<?php echo ($current_page == 'approve_user.php') ? 'active' : ''; ?>">
                <i class="fas fa-users-cog mr-3"></i> Manajemen User
            </a>
        <?php endif; ?>

        <a href="pengaturan.php" class="<?php echo ($current_page == 'pengaturan.php') ? 'active' : ''; ?>">
            <i class="fas fa-cog mr-3"></i> Pengaturan
        </a>
        
        <hr class="bg-secondary mx-3 mt-5 opacity-25">
        <a href="logout.php" class="text-danger mt-2">
            <i class="fas fa-power-off mr-3"></i> Logout
        </a>
    </div>
</div>

<!-- MAIN CONTENT WRAPPER -->
<div class="main-content">
    
    <!-- TOP NAVBAR -->
    <div class="top-navbar d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0 font-weight-bold text-dark">
                <?php 
                    if($current_page == 'dashboard.php') echo "Dashboard Utama";
                    elseif($current_page == 'data_komplain.php') echo "Daftar Laporan";
                    elseif($current_page == 'analisis_data.php') echo "Analisis Statistik";
                    elseif($current_page == 'pengaturan.php') echo "Pengaturan Akun";
                    else echo "Sistem Helpdesk";
                ?>
            </h5>
        </div>
        
        <div class="d-flex align-items-center">
            <div class="text-right mr-3 d-none d-md-block">
                <div class="font-weight-bold text-dark small" style="line-height: 1.2;"><?php echo $_SESSION['nama']; ?></div>
                <span class="badge badge-primary px-2 py-1" style="font-size: 10px;"><?php echo strtoupper($_SESSION['role']); ?></span>
            </div>
            
            <div class="dropdown">
                <img src="https://ui-avatars.com/api/?name=<?php echo $_SESSION['nama']; ?>&background=random&color=fff" 
                     class="rounded-circle user-avatar shadow-sm" 
                     data-toggle="dropdown" 
                     style="cursor:pointer" 
                     alt="User">
                <div class="dropdown-menu dropdown-menu-right shadow border-0">
                    <div class="px-4 py-2 small text-muted border-bottom mb-2">Kelola Akun</div>
                    <a class="dropdown-item py-2" href="pengaturan.php"><i class="fas fa-user-circle mr-2 text-primary"></i> Profil Saya</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item py-2 text-danger font-weight-bold" href="logout.php">
                        <i class="fas fa-sign-out-alt mr-2"></i> Keluar Aplikasi
                    </a>
                </div>
            </div>
        </div>
    </div>

<!-- Scripts (Wajib ada agar dropdown berfungsi) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>