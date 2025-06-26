<?php
// Sumar totales para mostrar en tarjeta resumen
$total_instalaciones = 0;
$total_areas = 0;
foreach ($instituciones as $inst) {
    $total_instalaciones += $model->contarInstalacionesPorInstitucion($inst['id']);
    $total_areas += $model->contarAreasPorInstitucion($inst['id']);
}
?>

<div style="display: flex; gap: 24px; align-items: flex-start;">
    <div style="flex: 1;">
        <h2 style="margin-bottom:12px;">
            <i class="fas fa-list-alt" style="color:#b81c22"></i> Instituciones Registradas
        </h2>
        <!-- Tarjetas resumen fuera de la tabla -->
        <div style="display:flex; gap: 24px; margin-bottom:16px;">
            <div style="
                background: #fff3f3;
                border: 1px solid #ffcaca;
                border-radius: 10px;
                padding: 18px 28px;
                min-width: 180px;
                box-shadow: 0 1px 4px rgba(184,28,34,0.03);
                display: flex;
                align-items: center;">
                <i class="fas fa-building" style="color:#b81c22; font-size:1.6em; margin-right:16px;"></i>
                <div>
                    <div style="font-size:1.4em; font-weight:bold; color:#b81c22;">
                        <?= $total_instalaciones ?>
                    </div>
                    <div style="font-size:1em; color:#b81c22;">Instalaciones deportivas</div>
                </div>
            </div>
            <div style="
                background: #fff3f3;
                border: 1px solid #ffcaca;
                border-radius: 10px;
                padding: 18px 28px;
                min-width: 180px;
                box-shadow: 0 1px 4px rgba(184,28,34,0.03);
                display: flex;
                align-items: center;">
                <i class="fas fa-futbol" style="color:#b81c22; font-size:1.6em; margin-right:16px;"></i>
                <div>
                    <div style="font-size:1.4em; font-weight:bold; color:#b81c22;">
                        <?= $total_areas ?>
                    </div>
                    <div style="font-size:1em; color:#b81c22;">Áreas deportivas</div>
                </div>
            </div>
        </div>
        <!-- Tabla de instituciones -->
        <?php if (!empty($instituciones)): ?>
            <div style="overflow-x:auto;">
            <table class="table table-striped" style="width:100%; min-width:900px;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>RUC</th>
                        <th>Dirección</th>
                        <th>Teléfono</th>
                        <th>Email</th>
                        <th>Calificación</th>
                        <th>Tarifa (S/.)</th>
                        <th># Instalaciones</th>
                        <th># Áreas</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($instituciones as $i => $inst): ?>
                        <tr>
                            <td><?= $i+1 ?></td>
                            <td><?= htmlspecialchars($inst['nombre']) ?></td>
                            <td><?= htmlspecialchars($inst['ruc_institucion']) ?></td>
                            <td><?= htmlspecialchars($inst['direccion']) ?></td>
                            <td>
                                <a href="https://wa.me/<?= preg_replace('/\D/', '', $inst['telefono']) ?>" target="_blank" rel="noopener noreferrer" title="Contactar por WhatsApp">
                                    <?= htmlspecialchars($inst['telefono']) ?>
                                    <i class="fab fa-whatsapp" style="color:#25d366;"></i>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($inst['email']) ?></td>
                            <td><?= number_format($inst['calificacion'], 1) ?></td>
                            <td><?= number_format($inst['tarifa'], 2) ?></td>
                            <td>
                                <?= $model->contarInstalacionesPorInstitucion($inst['id']) ?>
                            </td>
                            <td>
                                <?= $model->contarAreasPorInstitucion($inst['id']) ?>
                            </td>
                            <td>
                                <a href="ver_institucion.php?id=<?= $inst['id'] ?>" class="btn-small-inst btn-edit" title="Ver Detalles">
                                    <i class="fas fa-eye"></i> Ver Detalle
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        <?php else: ?>
            <p>No hay instituciones registradas.</p>
        <?php endif; ?>
    </div>
    <!-- Panel de ayuda existente -->
    <div style="min-width:330px;">
        <div class="ayuda-card">
            <div style="display:flex; align-items:center; gap:8px;">
                <i class="fas fa-question-circle" style="color:#b81c22; font-size:1.5em;"></i>
                <span style="font-weight:600; font-size:1.17em;">Ayuda</span>
            </div>
            <div style="margin-top:12px; color:#222;">
                Haz clic en una institución para ver más información o comparar servicios y precios.
            </div>
        </div>
    </div>
</div>