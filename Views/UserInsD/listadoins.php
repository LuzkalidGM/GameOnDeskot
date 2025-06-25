<?php
session_start();

// Verificar si el usuario está autenticado como instalación deportiva
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'instalacion') {
    header("Location: ../index.php");
    exit();
}

// Controlador de instituciones
require_once '../../Controllers/InstitucionesController.php';
$institucionesController = new InstitucionesController();
$instituciones = $institucionesController->listar();

// Incluir cabecera reutilizable
include_once 'header.php';
?>

<div class="dashboard-container-inst">
    <div class="welcome-banner-inst">
        <div class="welcome-content-inst">
            <h1>Listado de Instituciones Deportivas</h1>
            <p>Consulta, visualiza y compara todas las instituciones deportivas registradas en el sistema.</p>
        </div>
        <div class="welcome-actions-inst">
            <a href="dashboard.php" class="btn-secondary-inst"><i class="fas fa-arrow-left"></i> Volver al Panel</a>
        </div>
    </div>

    <div class="dashboard-main-content-inst">
        <div class="main-panel-inst">
            <div class="content-card-inst">
                <div class="card-header-inst">
                    <h2><i class="fas fa-list"></i> Instituciones Registradas</h2>
                </div>
                <!-- Aquí se muestra el listado -->
                <?php include 'partials/lista_instituciones.php'; ?>
            </div>
        </div>
        <div class="sidebar-panel-inst">
            <div class="sidebar-card-inst">
                <h3><i class="fas fa-info-circle"></i> Ayuda</h3>
                <div class="info-content">
                    <p>Haz clic en una institución para ver más información o comparar servicios y precios.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="../../Public/js/dashboard_instituciones.js"></script>
<?php include_once 'footer.php'; ?>