<?php
require_once '../models/Usuario.php';

class AuthService {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function login($correo, $contrasena) {
        $usuario = $this->usuarioModel->buscarPorCorreo($correo);
        
        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            session_start();
            $_SESSION['id_usuario'] = $usuario['id_usuario'];
            $_SESSION['nombre'] = $usuario['nombre'];
            $_SESSION['id_rol'] = $usuario['id_rol'];
            return true;
        }
        return false;
    }

    public function registro($nombre, $correo, $contrasena) {
        $existe = $this->usuarioModel->buscarPorCorreo($correo);
        
        if ($existe) {
            return false;
        }
        return $this->usuarioModel->crear($nombre, $correo, $contrasena);
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: ../index.php");
        exit();
    }
}
?>