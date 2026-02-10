<?php 
include 'config.php'; 
include 'menu.php'; 

// 1. DATA STATUS (KPI)
$res_c = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chat WHERE status='C'"))['total'];
$res_o = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chat WHERE status='O'"))['total'];
$res_u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chat WHERE status='U'"))['total'];
$res_f = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM chat WHERE status='F'"))['total'];

// 2. DATA BULANAN (Tahun Berjalan)
$monthly_data = array_fill(1, 12, 0);
$q_monthly = mysqli_query($conn, "SELECT MONTH(timestamp) as bulan, COUNT(*) as total FROM chat WHERE YEAR(timestamp) = YEAR(CURRENT_DATE) GROUP BY MONTH(timestamp)");
while($row = mysqli_fetch_assoc($q_monthly)) { $monthly_data[(int)$row['bulan']] = $row['total']; }
$monthly_values = implode(',', $monthly_data);

// 3. DATA TAHUNAN (Tren Historis)
$yearly_labels = [];
$yearly_values = [];
$q_yearly = mysqli_query($conn, "SELECT YEAR(timestamp) as tahun, COUNT(*) as total FROM chat GROUP BY YEAR(timestamp) ORDER BY YEAR(timestamp) ASC");
while($row = mysqli_fetch_assoc($q_yearly)) {
    $yearly_labels[] = $row['tahun'];
    $yearly_values[] = $row['total'];
}
$labels_tahun = "'" . implode("','", $yearly_labels) . "'";
$values_tahun = implode(',', $yearly_values);
?>

<div class="container-fluid px-4 mb-5">
    <!-- Header Halaman -->
    <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
        <div class="card-body p-4 d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-weight-bold mb-1"><i class="fas fa-chart-pie mr-2 text-primary"></i> Pusat Analisis & Statistik</h4>
                <p class="text-muted mb-0 small">Rangkuman performa sistem Helpdesk PINTech secara real-time.</p>
            </div>
            <button onclick="window.print()" class="btn btn-outline-primary btn-sm px-3 shadow-sm font-weight-bold">
                <i class="fas fa-print mr-1"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <div class="row">
        <!-- GRAFIK 1: STATUS KPI (Doughnut) -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 15px;">
                <div class="card-header bg-white font-weight-bold border-0 pt-4">Rangkuman Progres (%)</div>
                <div class="card-body">
                    <canvas id="statusChart" height="250"></canvas>
                </div>
                <div class="card-footer bg-white border-0 pb-4">
                    <div class="row text-center small">
                        <div class="col-3 text-secondary"><strong>C</strong><br><?php echo $res_c; ?></div>
                        <div class="col-3 text-primary"><strong>O</strong><br><?php echo $res_o; ?></div>
                        <div class="col-3 text-danger"><strong>U</strong><br><?php echo $res_u; ?></div>
                        <div class="col-3 text-success"><strong>F</strong><br><?php echo $res_f; ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRAFIK 2: BULANAN (Bar) -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 15px;">
                <div class="card-header bg-white font-weight-bold border-0 pt-4">Laporan Masuk Per Bulan (<?php echo date('Y'); ?>)</div>
                <div class="card-body">
                    <canvas id="monthlyChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- GRAFIK 3: TAHUNAN (Line) -->
        <div class="col-md-12">
            <div class="card shadow-sm border-0" style="border-radius: 15px;">
                <div class="card-header bg-white font-weight-bold border-0 pt-4 text-center">Tren Keluhan Per Tahun</div>
                <div class="card-body">
                    <div style="height: 350px;">
                        <canvas id="yearlyChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// 1. CHART STATUS
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Create', 'Open', 'Unfinish', 'Finish'],
        datasets: [{
            data: [<?php echo "$res_c, $res_o, $res_u, $res_f"; ?>],
            backgroundColor: ['#6c757d', '#007bff', '#dc3545', '#28a745'],
            borderWidth: 0
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

// 2. CHART BULANAN
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
        datasets: [{
            label: 'Total Komplain',
            data: [<?php echo $monthly_values; ?>],
            backgroundColor: '#3498db',
            borderRadius: 5
        }]
    },
    options: {
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});

// 3. CHART TAHUNAN
new Chart(document.getElementById('yearlyChart'), {
    type: 'line',
    data: {
        labels: [<?php echo $labels_tahun; ?>],
        datasets: [{
            label: 'Jumlah Laporan Tahunan',
            data: [<?php echo $values_tahun; ?>],
            borderColor: '#e67e22',
            backgroundColor: 'rgba(230, 126, 34, 0.1)',
            fill: true,
            tension: 0.4,
            pointRadius: 5,
            pointBackgroundColor: '#e67e22'
        }]
    },
    options: {
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
    }
});
</script>
</body>
</html>