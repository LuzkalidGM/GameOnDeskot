<?php
require_once __DIR__ . '/../../Controllers/UserController.php';

$controller = new UserController();
$data = $controller->registrarInstalacion();

$form_data = $data['form_data'];
$error_message = $data['error_message'];
$success_message = $data['success_message'];

// Redirección automática si el registro fue exitoso
if (!empty($success_message)) {
    // Espera 2 segundos y luego redirige a index.php (login)
    header("Refresh:2; url=../../index.php");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Instalación IPD - GameOn</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../Public/css/styles_registrousu.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="auth-page">
        <div class="auth-image">
            <div class="welcome-text">
                <h1>Registro de Instalación IPD</h1>
                <p>
                    Este registro es solo para instalaciones deportivas institucionales IPD.<br>
                    Completa los datos para solicitar tu acceso institucional.
                </p>
            </div>
            <div class="sponsors-section">
                <h2>IPD</h2>
                <img src="../../Resources/logo_ipd_2.png" alt="Logo IPD">
            </div>
        </div>
        <div class="auth-container">
            <div class="form-container">
                <h2>Registra tu Instalación IPD</h2>
                <p>Completa tus datos para acceder al sistema.</p>
                <?php if (!empty($error_message)): ?>
                    <div class="alert error"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <?php if (!empty($success_message)): ?>
                    <div class="alert success">
                        <?php echo $success_message; ?>
                        <br>Redirigiendo a la página de inicio de sesión...
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="username">Nombre de Usuario *</label>
                        <input id="username" type="text" name="username" value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input id="email" type="email" name="email" value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Contraseña *</label>
                            <input id="password" type="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirmar contraseña *</label>
                            <input id="confirm_password" type="password" name="confirm_password" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn-submit">Crear mi Cuenta</button>
                    </div>
                    <p class="login-link">¿Ya tienes cuenta? <a href="../../index.php">Inicia sesión</a></p>
                </form>
            </div>
        </div>
    </div>
</body>
</html>