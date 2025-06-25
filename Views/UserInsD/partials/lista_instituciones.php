<?php if (!empty($instituciones)): ?>
    <div class="instituciones-listado">
        <?php foreach ($instituciones as $inst): ?>
            <div class="instalacion-card-inst" style="max-width:400px; display:inline-block; margin:10px;">
                <div class="instalacion-imagen-inst">
                    <img src="<?= htmlspecialchars($inst['imagen'] ?: '../../Resources/default-avatar.png') ?>"
                         alt="<?= htmlspecialchars($inst['nombre']) ?>" style="width:100%; height:120px; object-fit:cover;">
                    <div class="instalacion-estado-inst <?= $inst['estado'] ? 'activa' : 'inactiva' ?>">
                        <?= $inst['estado'] ? 'Activa' : 'Inactiva' ?>
                    </div>
                </div>
                <div class="instalacion-info-inst">
                    <h4><?= htmlspecialchars($inst['nombre']) ?></h4>
                    <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($inst['direccion']) ?></p>
                    <p><i class="fas fa-id-card"></i> RUC: <?= htmlspecialchars($inst['ruc_institucion']) ?></p>
                    <p><i class="fas fa-phone"></i> <?= htmlspecialchars($inst['telefono']) ?></p>
                    <p><i class="fas fa-envelope"></i> <?= htmlspecialchars($inst['email']) ?></p>
                    <p><i class="fas fa-star"></i> <?= number_format($inst['calificacion'], 1) ?></p>
                    <p><i class="fas fa-dollar-sign"></i> S/. <?= number_format($inst['tarifa'], 2) ?> promedio/hora</p>
                </div>
                <div class="instalacion-actions-inst">
                    <a href="ver_institucion.php?id=<?= $inst['id'] ?>" class="btn-small-inst btn-edit">
                        <i class="fas fa-eye"></i> Ver Detalle
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p>No hay instituciones registradas.</p>
<?php endif; ?>