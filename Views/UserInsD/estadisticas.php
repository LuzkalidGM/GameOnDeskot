<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'instalacion') {
    header("Location: ../index.php");
    exit();
}

require_once '../../Config/Database.php';
$conn = (new Database())->getConnection();

// 1. Cantidad de instituciones registradas por mes/año (GENERAL)
$institucionesPorMes = [];
$sql1 = "SELECT DATE_FORMAT(creado_en, '%Y-%m') as periodo, COUNT(*) as cantidad 
         FROM instituciones_deportivas 
         GROUP BY periodo ORDER BY periodo";
$res1 = $conn->query($sql1);
while ($row = $res1->fetch_assoc()) {
    $institucionesPorMes[] = $row;
}

// 2. Distribución de áreas deportivas por tipo de deporte (GENERAL)
$areasPorDeporte = [];
$sql2 = "SELECT d.nombre as deporte, COUNT(*) as cantidad 
         FROM areas_deportivas ad
         JOIN deportes d ON ad.deporte_id = d.id
         GROUP BY ad.deporte_id";
$res2 = $conn->query($sql2);
while ($row = $res2->fetch_assoc()) {
    $areasPorDeporte[] = $row;
}

// 3. Evolución de reservas por mes (GENERAL)
$reservasPorMes = [];
$sql3 = "SELECT DATE_FORMAT(fecha, '%Y-%m') as periodo, COUNT(*) as cantidad 
         FROM reservas 
         GROUP BY periodo ORDER BY periodo";
$res3 = $conn->query($sql3);
while ($row = $res3->fetch_assoc()) {
    $reservasPorMes[] = $row;
}

// 4. Torneos según fecha de inicio por mes (GENERAL)
$torneosPorMes = [];
$sqlTorneos = "SELECT DATE_FORMAT(fecha_inicio, '%Y-%m') as periodo, COUNT(*) as cantidad 
               FROM torneos 
               GROUP BY periodo ORDER BY periodo";
$resTorneos = $conn->query($sqlTorneos);
while ($row = $resTorneos->fetch_assoc()) {
    $torneosPorMes[] = $row;
}

include_once 'header.php';
?>
<link rel="stylesheet" href="../../Public/cssInsDepor/estadisticas.css">

<div class="dashboard-container-inst">
    <div class="dashboard-main-content-inst">
        <div class="main-panel-inst">
            <div class="content-card-inst">
                <div class="card-header-inst">
                    <h2><i class="fas fa-chart-bar"></i> Estadísticas Generales del Sistema</h2>
                </div>
                <!-- PRIMERA FILA - 3 GRAFICOS -->
                <div class="dashboard-grid dashboard-grid-3">
                    <div class="dashboard-card">
                        <h3>Instituciones registradas por mes</h3>
                        <canvas id="graficoInstituciones" height="160"></canvas>
                    </div>
                    <div class="dashboard-card">
                        <h3>Áreas por deporte</h3>
                        <canvas id="graficoAreas" height="160"></canvas>
                    </div>
                    <div class="dashboard-card">
                        <h3>Torneos que inician por mes</h3>
                        <canvas id="graficoTorneos" height="160"></canvas>
                    </div>
                </div>
                <!-- SEGUNDA FILA - 1 GRAFICO ANCHO -->
                <div class="dashboard-grid dashboard-grid-1">
                    <div class="dashboard-card" style="min-width:0;">
                        <h3>Reservas por mes</h3>
                        <canvas id="graficoReservas" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
/* 1. Instituciones por mes */
const institucionesLabels = <?= json_encode(array_column($institucionesPorMes, 'periodo')) ?>;
const institucionesData = <?= json_encode(array_column($institucionesPorMes, 'cantidad')) ?>;
new Chart(document.getElementById('graficoInstituciones'), {
    type: 'bar',
    data: {
        labels: institucionesLabels,
        datasets: [{
            label: 'Instituciones registradas',
            data: institucionesData,
            backgroundColor: '#1976d2'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: "#e0e0e0" } },
            x: { grid: { color: "#f1f1f1" } }
        }
    }
});

/* 2. Áreas por deporte */
const areasLabels = <?= json_encode(array_column($areasPorDeporte, 'deporte')) ?>;
const areasData = <?= json_encode(array_column($areasPorDeporte, 'cantidad')) ?>;
new Chart(document.getElementById('graficoAreas'), {
    type: 'pie',
    data: {
        labels: areasLabels,
        datasets: [{
            label: 'Áreas por deporte',
            data: areasData,
            backgroundColor: ['#43a047','#ff7043','#fbc02d','#7986cb','#8d6e63','#26a69a','#d4e157','#ef5350','#7e57c2']
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});

/* 3. Reservas por mes */
const reservasLabels = <?= json_encode(array_column($reservasPorMes, 'periodo')) ?>;
const reservasData = <?= json_encode(array_column($reservasPorMes, 'cantidad')) ?>;
new Chart(document.getElementById('graficoReservas'), {
    type: 'line',
    data: {
        labels: reservasLabels,
        datasets: [{
            label: 'Reservas',
            data: reservasData,
            fill: true,
            borderColor: '#d81b60',
            backgroundColor: 'rgba(216,27,96,0.13)',
            tension: 0.4,
            pointRadius: 5,
            pointBackgroundColor: '#d81b60'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: "#e0e0e0" } },
            x: { grid: { color: "#f1f1f1" } }
        }
    }
});

/* 4. Torneos por mes (según fecha de inicio) */
const torneosLabels = <?= json_encode(array_column($torneosPorMes, 'periodo')) ?>;
const torneosData = <?= json_encode(array_column($torneosPorMes, 'cantidad')) ?>;
new Chart(document.getElementById('graficoTorneos'), {
    type: 'bar',
    data: {
        labels: torneosLabels,
        datasets: [{
            label: 'Torneos',
            data: torneosData,
            backgroundColor: '#673ab7'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: "#e0e0e0" } },
            x: { grid: { color: "#f1f1f1" } }
        }
    }
});
</script>
<?php include_once 'footer.php'; ?>