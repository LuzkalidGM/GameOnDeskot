<?php 
require_once __DIR__ . '/../../Controllers/SolicitudController.php'; 
// El controlador ya se encarga de la sesión y de obtener los datos.

// Incluir cabecera
include_once 'header.php';
?>
<link rel="stylesheet" href="../../Public/cssInsDepor/gestionar_solicitudes.css">

<div class="container">
    <h1>Gestión de Solicitudes de Registro</h1>

    <?php if ($message): ?>
        <p style="color: green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <div class="tabs">
        <div class="tab-link active" onclick="openTab(event, 'pendientes')">Pendientes (<?php echo count($solicitudes_pendientes); ?>)</div>
        <div class="tab-link" onclick="openTab(event, 'aprobadas')">Aprobadas (<?php echo count($solicitudes_aprobadas); ?>)</div>
        <div class="tab-link" onclick="openTab(event, 'rechazadas')">Rechazadas (<?php echo count($solicitudes_rechazadas); ?>)</div>
    </div>

    <!-- Pestaña de Pendientes -->
    <div id="pendientes" class="tab-content active">
        <h2>Solicitudes Pendientes</h2>
        <table>
            <thead>
                <tr>
                    <th>Institución</th>
                    <th>RUC</th>
                    <th>Email</th>
                    <th>Fecha Solicitud</th>
                    <th>Documento</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitudes_pendientes as $solicitud): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($solicitud['nombre_institucion']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['ruc']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['email']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                                                <td><a href="<?php echo htmlspecialchars($solicitud['documento_path']); ?>" target="_blank">Ver PDF</a></td>
                        <td class="actions">
                            <form action="" method="POST">
                                <input type="hidden" name="solicitud_id" value="<?php echo $solicitud['id']; ?>">
                                <input type="hidden" name="action" value="aprobar">
                                <button type="submit" class="btn-approve" onclick="return confirm('¿Estás seguro de que quieres aprobar esta solicitud?');">Aprobar</button>
                            </form>
                            <button class="btn-reject" onclick="openRejectModal(<?php echo $solicitud['id']; ?>)">Rechazar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($solicitudes_pendientes)): ?>
                    <tr><td colspan="6">No hay solicitudes pendientes.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pestaña de Aprobadas -->
    <div id="aprobadas" class="tab-content">
        <h2>Solicitudes Aprobadas</h2>
        <table>
            <thead>
                <tr>
                    <th>Institución</th>
                    <th>RUC</th>
                    <th>Email</th>
                    <th>Fecha Solicitud</th>
                    <th>Fecha Revisión</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitudes_aprobadas as $solicitud): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($solicitud['nombre_institucion']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['ruc']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['email']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['fecha_revision'] ?? 'N/A'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($solicitudes_aprobadas)): ?>
                    <tr><td colspan="5">No hay solicitudes aprobadas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pestaña de Rechazadas -->
    <div id="rechazadas" class="tab-content">
        <h2>Solicitudes Rechazadas</h2>
        <table>
            <thead>
                <tr>
                    <th>Institución</th>
                    <th>RUC</th>
                    <th>Email</th>
                    <th>Fecha Solicitud</th>
                    <th>Motivo del Rechazo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($solicitudes_rechazadas as $solicitud): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($solicitud['nombre_institucion']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['ruc']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['email']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['fecha_solicitud']); ?></td>
                        <td><?php echo htmlspecialchars($solicitud['motivo_rechazo'] ?? 'No especificado'); ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($solicitudes_rechazadas)): ?>
                    <tr><td colspan="5">No hay solicitudes rechazadas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- El Modal para Rechazar -->
<div id="rejectModal" class="modal">
    <div class="modal-content">
        <span class="close-button" onclick="closeRejectModal()">&times;</span>
        <h2>Motivo del Rechazo</h2>
        <form action="" method="POST">
            <input type="hidden" name="solicitud_id" id="modal_solicitud_id">
            <input type="hidden" name="action" value="rechazar">
            <textarea name="motivo_rechazo" rows="4" style="width: 100%;" placeholder="Explica por qué se rechaza la solicitud..." required></textarea>
            <br><br>
            <button type="submit" class="btn-reject">Confirmar Rechazo</button>
        </form>
    </div>
</div>

<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tab-link");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    function openRejectModal(solicitudId) {
        document.getElementById('modal_solicitud_id').value = solicitudId;
        document.getElementById('rejectModal').style.display = 'block';
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').style.display = 'none';
    }
</script>

<?php if (isset($email_params)): ?>
<!-- Incluir la librería de EmailJS -->
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>

<script type="text/javascript">
    // Inicializa EmailJS con tu clave pública.
    // TODO: Reemplaza 'YOUR_PUBLIC_KEY' con tu clave pública de EmailJS.
    (function() {
        emailjs.init('Kg-QrgkrkkcEyD0MZ');
    })();

    // Espera a que el DOM esté completamente cargado para ejecutar el script.
    document.addEventListener('DOMContentLoaded', function() {
        // Convierte los parámetros de PHP a un objeto JavaScript.
        const emailParams = <?php echo json_encode($email_params); ?>;

        // Prepara los parámetros para la plantilla de EmailJS.
        const templateParams = {
            to_email: emailParams.to_email,
            nombre_institucion: emailParams.nombre_institucion,
            username: emailParams.username || '', // Asegura que no sea undefined
            password: emailParams.password || '', // Asegura que no sea undefined
            motivo_rechazo: emailParams.motivo_rechazo || '' // Asegura que no sea undefined
        };

        if (emailParams.action === 'aprobar') {
            // TODO: Reemplaza con tu Service ID y Template ID para la plantilla de APROBACIÓN.
            const serviceID = 'service_gameon';
            const templateID = 'template_4tf6l3d';

            console.log("Enviando correo de aprobación a:", templateParams.to_email);
            emailjs.send(serviceID, templateID, templateParams)
                .then(function(response) {
                   console.log('ÉXITO!', response.status, response.text);
                   alert('La solicitud fue aprobada y el correo de notificación ha sido enviado.');
                }, function(error) {
                   console.log('FALLÓ...', error);
                   alert('La solicitud fue aprobada, pero hubo un error al enviar el correo de notificación. Revisa la consola para más detalles.');
                });

        } else if (emailParams.action === 'rechazar') {
            // TODO: Reemplaza con tu Service ID y Template ID para la plantilla de RECHAZO.
            const serviceID = 'service_gameon';
            const templateID = 'template_4tf6l3d0';

            console.log("Enviando correo de rechazo a:", templateParams.to_email);
            emailjs.send(serviceID, templateID, templateParams)
                .then(function(response) {
                   console.log('ÉXITO!', response.status, response.text);
                   alert('La solicitud fue rechazada y el correo de notificación ha sido enviado.');
                }, function(error) {
                   console.log('FALLÓ...', error);
                   alert('La solicitud fue rechazada, pero hubo un error al enviar el correo de notificación. Revisa la consola para más detalles.');
                });
        }
    });
</script>
<?php endif; ?>

<?php
// Incluir pie de página
include_once 'footer.php';
?>
