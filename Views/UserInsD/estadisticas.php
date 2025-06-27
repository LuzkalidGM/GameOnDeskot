<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'instalacion') {
    header("Location: ../index.php");
    exit();
}

require_once '../../Models/InstitucionesModel.php';
$model = new InstitucionesModel();

// 1. Cantidad de instituciones registradas por mes/año
$institucionesPorMes = [];
$conn = (new Database())->getConnection();
$sql1 = "SELECT DATE_FORMAT(creado_en, '%Y-%m') as periodo, COUNT(*) as cantidad 
         FROM instituciones_deportivas GROUP BY periodo ORDER BY periodo";
$res1 = $conn->query($sql1);
while ($row = $res1->fetch_assoc()) {
    $institucionesPorMes[] = $row;
}

// 2. Distribución de áreas deportivas por tipo de deporte
$areasPorDeporte = [];
$sql2 = "SELECT d.nombre as deporte, COUNT(*) as cantidad 
         FROM areas_deportivas ad
         JOIN deportes d ON ad.deporte_id = d.id
         GROUP BY ad.deporte_id";
$res2 = $conn->query($sql2);
while ($row = $res2->fetch_assoc()) {
    $areasPorDeporte[] = $row;
}

// 3. Evolución de reservas por mes
$reservasPorMes = [];
$sql3 = "SELECT DATE_FORMAT(fecha, '%Y-%m') as periodo, COUNT(*) as cantidad 
         FROM reservas GROUP BY periodo ORDER BY periodo";
$res3 = $conn->query($sql3);
while ($row = $res3->fetch_assoc()) {
    $reservasPorMes[] = $row;
}

// 4. Estado de instituciones
$estadosInstitucion = [];
$sql4 = "SELECT CASE estado WHEN 1 THEN 'Activa' ELSE 'Inactiva' END as estado, COUNT(*) as cantidad 
         FROM instituciones_deportivas GROUP BY estado";
$res4 = $conn->query($sql4);
while ($row = $res4->fetch_assoc()) {
    $estadosInstitucion[] = $row;
}

include_once 'header.php';
?>
<div class="dashboard-container-inst">
    <div class="dashboard-main-content-inst">
        <div class="main-panel-inst">
            <div class="content-card-inst" style="max-width:1200px; margin:auto;">
                <div class="card-header-inst">
                    <h2><i class="fas fa-chart-bar"></i> Estadísticas del Sistema</h2>
                </div>
                <div style="display: flex; flex-wrap:wrap; gap: 40px;">
                    <!-- Gráfico 1 -->
                    <div style="flex:1; min-width:320px;">
                        <h3 style="text-align:center;">Instituciones registradas por mes</h3>
                        <canvas id="graficoInstituciones"></canvas>
                    </div>
                    <!-- Gráfico 2 -->
                    <div style="flex:1; min-width:320px;">
                        <h3 style="text-align:center;">Áreas por deporte</h3>
                        <canvas id="graficoAreas"></canvas>
                    </div>
                </div>
                <div style="display: flex; flex-wrap:wrap; gap: 40px; margin-top:40px;">
                    <!-- Gráfico 3 -->
                    <div style="flex:1; min-width:320px;">
                        <h3 style="text-align:center;">Reservas por mes</h3>
                        <canvas id="graficoReservas"></canvas>
                    </div>
                    <!-- Gráfico 4 -->
                    <div style="flex:1; min-width:320px;">
                        <h3 style="text-align:center;">Estado de Instituciones</h3>
                        <canvas id="graficoEstados"></canvas>
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
        plugins: { legend: { display: false } }
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
            backgroundColor: 'rgba(216,27,96,0.1)'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } }
    }
});

/* 4. Estado de instituciones */
const estadosLabels = <?= json_encode(array_column($estadosInstitucion, 'estado')) ?>;
const estadosData = <?= json_encode(array_column($estadosInstitucion, 'cantidad')) ?>;
new Chart(document.getElementById('graficoEstados'), {
    type: 'pie',
    data: {
        labels: estadosLabels,
        datasets: [{
            label: 'Estado',
            data: estadosData,
            backgroundColor: ['#0ca678','#e57373','#ffd740','#64b5f6']
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom' } }
    }
});
</script>
<?php include_once 'footer.php'; ?>