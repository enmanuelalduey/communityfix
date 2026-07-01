<?php
require_once __DIR__ . '/../models/Usuario.php';

class AuthService {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login($correo, $contrasena) {
        $usuario = $this->usuarioModel->buscarPorCorreo($correo);

        if (!$usuario || !password_verify($contrasena, $usuario['contrasena'])) {
            return false;
        }

        if (session_status() === PHP_SESSION_NONE && php_sapi_name() !== 'cli') {
            session_start();
        }

        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre']     = $usuario['nombre'];
            $_SESSION['id_rol']     = $usuario['id_rol'];
        }

        return true;
    }

    public function registro($nombre, $correo, $contrasena) {
        $existe = $this->usuarioModel->buscarPorCorreo($correo);
        if ($existe) {
            return false;
        }
        return $this->usuarioModel->crear($nombre, $correo, $contrasena);
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        header("Location: /communityfix/?action=login");
        exit();
    }
}
?>