<?php
//session_start();
require_once __DIR__ . '/../Models/UsuarioModel.php';

class AuthController {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new UsuarioModel();
    }

    public function login($username, $password, $user_type) {
        // Solo aceptar instalaciones
        if ($user_type !== 'instalacion') {
            return "Solo pueden iniciar sesión las instalaciones deportivas.";
        }

        try {
            $usuario = $this->usuarioModel->obtenerUsuarioPorUsername($username, $user_type);

            if (!$usuario) {
                return "Credenciales incorrectas.";
            }

            if (!password_verify($password, $usuario['password'])) {
                return "Contraseña incorrecta.";
            }

            if ($usuario['estado'] != 1) {
                return "Tu cuenta no está activa.";
            }

            // Autenticación exitosa: iniciar sesión
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['username'] = $usuario['username'];
            $_SESSION['user_type'] = $user_type;

            // Solo redirige a instalaciones
            header("Location: Views/UserInsD/dashboard.php");
            exit();
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            return "Error interno del sistema. Por favor, inténtalo de nuevo.";
        }
    }
}
?>