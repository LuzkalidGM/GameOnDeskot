<?php
session_start();

// Verificar autenticación
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'instalacion') {
    header("Location: ../index.php");
    exit();
}

// Sanitizar ID recibido por GET
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<p>Institución no válida.</p>";
    exit();
}

// Importar el modelo
require_once '../../Models/InstitucionesModel.php';
$model = new InstitucionesModel();
$institucion = $model->obtenerInstitucionPorId($id);
$areas = $model->obtenerAreasPorInstitucion($id);
include_once 'header.php';
?>

<div class="dashboard-container-inst">
    <div class="dashboard-main-content-inst">
        <div class="main-panel-inst">
            <div class="content-card-inst" style="max-width:900px; margin:auto;">
                <div class="card-header-inst">
                    <h2><i class="fas fa-building"></i> Detalle de Institución Deportiva</h2>
                    <a href="listadoins.php" class="btn-outline-inst"><i class="fas fa-arrow-left"></i> Volver al Listado</a>
                </div>
                <?php if ($institucion): ?>
                    <div style="display:flex;gap:30px;align-items:flex-start;flex-wrap:wrap;">
                        <div>
                            <img src="<?= htmlspecialchars($institucion['imagen'] ?: '../../Resources/default-avatar.png') ?>"
                                 alt="Logo" style="width:160px; height:160px; border-radius:12px; object-fit:cover;">
                        </div>
                        <div style="flex:1;">
                            <h3><?= htmlspecialchars($institucion['nombre']) ?></h3>
                            <p><strong>RUC:</strong> <?= htmlspecialchars($institucion['ruc_institucion']) ?></p>
                            <p><strong>Dirección:</strong> <?= htmlspecialchars($institucion['direccion']) ?></p>
                            <p><strong>Teléfono:</strong> <?= htmlspecialchars($institucion['telefono']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($institucion['email']) ?></p>
                            <p><strong>Tarifa:</strong> S/. <?= number_format($institucion['tarifa'], 2) ?></p>
                            <p><strong>Calificación:</strong> <?= number_format($institucion['calificacion'], 1) ?></p>
                            <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($institucion['descripcion'])) ?></p>
                            <p><strong>Ubicación:</strong>
                                <a href="https://maps.google.com/?q=<?= $institucion['latitud'] ?>,<?= $institucion['longitud'] ?>" target="_blank">
                                    Ver en Google Maps
                                </a>
                            </p>

                            <?php if (!empty($institucion['latitud']) && !empty($institucion['longitud'])): ?>
                                <div id="mapaInstitucion" style="width:100%;max-width:600px;height:350px;margin:16px 0;border-radius:12px;box-shadow:0 2px 8px #0002;"></div>
                                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
                                <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        var map = L.map('mapaInstitucion').setView([<?= $institucion['latitud'] ?>, <?= $institucion['longitud'] ?>], 16);
                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                        }).addTo(map);
                                        L.marker([<?= $institucion['latitud'] ?>, <?= $institucion['longitud'] ?>])
                                            .addTo(map)
                                            .bindPopup("<?= htmlspecialchars($institucion['nombre']) ?>")
                                            .openPopup();
                                    });
                                </script>
                            <?php endif; ?>
                        </div>
                    </div>
                    <hr>
                    <!-- Áreas deportivas asociadas -->
                    <h4>Áreas Deportivas Asociadas</h4>
                    <?php if (!empty($areas)): ?>
                        <div style="overflow-x:auto;">
                        <table class="table table-striped" style="width:100%; min-width:600px;">
                            <thead>
                                <tr>
                                    <th>Nombre Área</th>
                                    <th>Deporte</th>
                                    <th>Capacidad</th>
                                    <th>Tarifa (S/.)</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($areas as $area): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($area['nombre_area']) ?></td>
                                        <td><?= htmlspecialchars($area['deporte_nombre']) ?></td>
                                        <td><?= htmlspecialchars($area['capacidad_jugadores']) ?></td>
                                        <td><?= number_format($area['tarifa_por_hora'], 2) ?></td>
                                        <td><?= ucfirst($area['estado']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        </div>
                    <?php else: ?>
                        <p>No hay áreas deportivas asociadas.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="alert alert-danger">
                        <p>No se encontró la institución solicitada.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once 'footer.php'; ?>