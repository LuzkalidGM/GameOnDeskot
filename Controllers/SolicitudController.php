<?php
session_start();
require_once __DIR__ . '/../Models/Solicitud.php';

// Simulación de ID de administrador - En un sistema real, esto vendría de la sesión
if (!isset($_SESSION['admin_id'])) {
    // Para este ejemplo, asumiremos que el admin con ID 1 está logueado.
    // En tu implementación final, deberías tener un login de admin que establezca este valor.
    $_SESSION['admin_id'] = 1; 
}
$admin_id = $_SESSION['admin_id'];

$solicitudModel = new Solicitud();
$message = '';
$error = '';

// Procesar acciones de POST (aprobar/rechazar)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $solicitud_id = $_POST['solicitud_id'] ?? 0;
    $solicitud_data = $solicitudModel->obtenerPorId($solicitud_id);

    if ($action === 'aprobar' && $solicitud_data) {
        $nuevo_usuario_id = $solicitudModel->aprobar($solicitud_id, $admin_id);
        if ($nuevo_usuario_id) {
            $message = "Solicitud aprobada con éxito. Se ha creado el nuevo usuario.";
            // Preparar datos para el correo de aprobación
            $email_params = [
                'action' => 'aprobar',
                'to_email' => $solicitud_data['email'],
                'nombre_institucion' => $solicitud_data['nombre_institucion'],
                'username' => $solicitud_data['email'], // El nombre de usuario es el email
                'password' => $solicitud_data['password'] // La contraseña ya existe en la solicitud
            ];
        } else {
            $error = $solicitudModel->error ?? "Error al aprobar la solicitud.";
        }
    } elseif ($action === 'rechazar' && $solicitud_data) {
        $motivo = $_POST['motivo_rechazo'] ?? 'No se especificó un motivo.';
        if ($solicitudModel->rechazar($solicitud_id, $admin_id, $motivo)) {
            $message = "Solicitud rechazada correctamente.";
            // Preparar datos para el correo de rechazo
            $email_params = [
                'action' => 'rechazar',
                'to_email' => $solicitud_data['email'],
                'nombre_institucion' => $solicitud_data['nombre_institucion'],
                'motivo_rechazo' => $motivo
            ];
        } else {
            $error = "Error al rechazar la solicitud.";
        }
    }
}

// Obtener todas las solicitudes para mostrarlas en la vista
$solicitudes_pendientes = $solicitudModel->obtenerTodas('pendiente');
$solicitudes_aprobadas = $solicitudModel->obtenerTodas('aprobada');
$solicitudes_rechazadas = $solicitudModel->obtenerTodas('rechazada');

?>
