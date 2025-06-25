<?php if (!empty($instituciones)): ?>
    <div style="overflow-x:auto;">
    <table class="table table-striped" style="width:100%; min-width:750px;">
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
                        <a href="https://wa.me/<?= preg_replace('/\D/', '', $inst['telefono']) ?>" target="_blank" title="Contactar por WhatsApp">
                            <?= htmlspecialchars($inst['telefono']) ?>
                            <i class="fab fa-whatsapp" style="color:#25d366;"></i>
                        </a>
                    </td>
                    <td><?= htmlspecialchars($inst['email']) ?></td>
                    <td><?= number_format($inst['calificacion'], 1) ?></td>
                    <td><?= number_format($inst['tarifa'], 2) ?></td>
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

