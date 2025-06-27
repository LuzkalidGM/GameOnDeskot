<?php
session_start();
require_once 'Controllers/AuthController.php';

// Si ya está logueado, redirige al dashboard de instalaciones
if (isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'instalacion') {
    header("Location: Views/UserInsD/dashboard.php");
    exit();
}

$error_message = '';
$username = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error_message = "Por favor, completa todos los campos.";
    } else {
        // Hardcodea el tipo de usuario como "instalacion"
        $authController = new AuthController();
        $error_message = $authController->login($username, $password, 'instalacion');
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - IPD GameOn</title>
    <link rel="stylesheet" href="Public/css/styles_login.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="auth-page">
        <!-- Panel Izquierdo -->
        <div class="auth-image">
            <div class="welcome-content">
                
                <h1>
                    Bienvenidos al<br>
                    <span style="font-style: italic; font-weight: 700;">Campus Virtual</span> del IPD
                </h1>
                <p style="margin-top:30px;font-size:1.15rem; color:#fff; font-weight:400;">
                    "...Tacna juega, GameOn conecta..."
                </p>
            </div>
        </div>
        <!-- Panel Derecho -->
        <div class="auth-container">
            <div class="auth-header">
                <img src="logo_ipd.png" alt="IPD Logo" class="ipd-logo">                
                <h2>Ingresa al Campus Virtual</h2>
            </div>
            
            <div class="auth-body">
                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" id="loginForm" autocomplete="off">
                    <div class="form-group">
                        <label for="username">
                            <i class="fas fa-user"></i>
                            Usuario
                        </label>
                        <input type="text" id="username" name="username" class="form-control" 
                               value="<?php echo htmlspecialchars($username); ?>" 
                               placeholder="Ingresa tu usuario" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">
                            <i class="fas fa-lock"></i>
                            Contraseña
                        </label>
                        <div class="password-field">
                            <input type="password" id="password" name="password" class="form-control" 
                                   placeholder="Ingresa tu contraseña" required>
                            <span class="toggle-password" onclick="togglePasswordVisibility()">
                                <i class="fas fa-eye"></i>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">
                                <i class="fas fa-check-square"></i>
                                Recordarme
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block" style="margin-top:10px;">
                        <i class="fas fa-sign-in-alt"></i>
                        Ingresar
                    </button>
                </form>
                
                <div class="forgot-password text-center" style="margin-top:10px;">
                    <a href="Views/Auth/forgot-password.php">
                        <i class="fas fa-question-circle"></i>
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </div>
            
            <!-- 
            <div class="auth-footer">
                ¿No tienes cuenta? 
                <a href="Views/UserInsD/registro_deportista_ipd.php">
                    <i class="fas fa-building"></i>
                    Registrate
                </a>
            </div> -->
        </div>
    </div>
    
    <script>
        function togglePasswordVisibility() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Iniciando sesión...';
            submitBtn.disabled = true;
        });
    </script>
    <script src="Public/js/main.js"></script>
</body>
</html>