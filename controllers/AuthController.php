<?php
require_once '../services/AuthService.php';

class AuthController {
    private $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function login() {
        $error = "";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $correo = trim($_POST['correo']);
            $contrasena = $_POST['contrasena'];

            if (empty($correo) || empty($contrasena)) {
                $error = "Por favor completa todos los campos.";
            } else {
                $resultado = $this->authService->login($correo, $contrasena);
                if ($resultado) {
                    header("Location: ../index.php");
                    exit();
                } else {
                    $error = "Correo o contraseña incorrectos.";
                }
            }
        }
        include '../views/login.html';
    }

    public function registro() {
        $error = "";
        $exito = "";
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nombre = trim($_POST['nombre']);
            $correo = trim($_POST['correo']);
            $contrasena = $_POST['contrasena'];

            if (empty($nombre) || empty($correo) || empty($contrasena)) {
                $error = "Por favor completa todos los campos.";
            } else {
                $resultado = $this->authService->registro($nombre, $correo, $contrasena);
                if ($resultado) {
                    $exito = "Usuario registrado correctamente.";
                } else {
                    $error = "El correo ya está registrado.";
                }
            }
        }
        include '../views/registro.html';
    }

    public function logout() {
        $this->authService->logout();
    }
}
?>