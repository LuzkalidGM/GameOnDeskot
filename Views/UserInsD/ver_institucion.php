<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'instalacion') {
    header("Location: ../index.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    echo "<p>Institución no válida.</p>";
    exit();
}

require_once '../../Models/InstitucionesModel.php';
$model = new InstitucionesModel();
$institucion = $model->obtenerInstitucionPorId($id);

$instalaciones = method_exists($model, 'obtenerInstalacionesPorInstitucion') 
    ? $model->obtenerInstalacionesPorInstitucion($id) : [];
$areas = method_exists($model, 'obtenerAreasPorInstitucion') 
    ? $model->obtenerAreasPorInstitucion($id) : [];

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
                        </div>
                    </div>
                    <hr>
                    <!-- Instalaciones asociadas (opcional, según tu estructura) -->
                    <?php if (!empty($instalaciones)): ?>
                    <h4>Instalaciones asociadas</h4>
                    <ul>
                        <?php foreach ($instalaciones as $inst): ?>
                            <li>
                                <strong><?= htmlspecialchars($inst['nombre']) ?></strong>
                                <?php if (!empty($inst['direccion'])): ?>
                                    - <?= htmlspecialchars($inst['direccion']) ?>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>

                    <!-- Áreas deportivas asociadas -->
                    <h4>Áreas Deportivas</h4>
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