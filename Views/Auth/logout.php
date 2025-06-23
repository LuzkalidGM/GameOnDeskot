<?php
    session_start();

    // Destruir todas las variables de sesión
    $_SESSION = array();

    // Destruir la cookie de sesión si existe
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destruir la sesión
    session_destroy();

    // Redirigir al login (index.php) con mensaje
    // Obtiene el path base automáticamente
    require_once __DIR__ . '/../../config.php';
    header("Location: " . BASE_URL . "/index.php?message=Sesión cerrada correctamente");
    exit();
?>