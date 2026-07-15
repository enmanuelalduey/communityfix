<?php
require_once __DIR__ . '/../services/AuthService.php';

class AuthController {
    private AuthService $authService;

    public function __construct() {
        $this->authService = new AuthService();
    }

    public function login(): void {
        $error = '';

        if (isset($_SESSION['id_usuario'])) {
            if ($_SESSION['id_rol'] == 1) {
                header('Location: /communityfix/?action=admin');
            } else {
                header('Location: /communityfix/views/dashboard.php');
            }
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo     = trim($_POST['correo']     ?? '');
            $contrasena = $_POST['contrasena'] ?? '';

            if (empty($correo) || empty($contrasena)) {
                $error = 'Por favor completa todos los campos.';
            } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $error = 'Ingresa un correo válido.';
            } elseif ($this->authService->login($correo, $contrasena)) {
                if ($_SESSION['id_rol'] == 1) {
                    header('Location: /communityfix/?action=admin');
                } else {
                    header('Location: /communityfix/views/dashboard.php');
                }
                exit();
            } else {
                $error = 'Correo o contraseña incorrectos.';
            }
        }

        include __DIR__ . '/../views/login.php';
    }

    public function registro(): void {
        $error = '';
        $exito = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre     = trim($_POST['nombre']     ?? '');
            $correo     = trim($_POST['correo']     ?? '');
            $contrasena = $_POST['contrasena'] ?? '';
            $confirmar  = $_POST['confirmar']  ?? '';

            if (empty($nombre) || empty($correo) || empty($contrasena) || empty($confirmar)) {
                $error = 'Por favor completa todos los campos.';
            } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $error = 'Ingresa un correo electrónico válido.';
            } elseif (strlen($contrasena) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres.';
            } elseif ($contrasena !== $confirmar) {
                $error = 'Las contraseñas no coinciden.';
            } elseif ($this->authService->registro($nombre, $correo, $contrasena)) {
                header('Location: /communityfix/?action=login&registro=1');
                exit();
            } else {
                $error = 'Este correo ya está registrado.';
            }
        }

        include __DIR__ . '/../views/registro.php';
    }

    public function logout(): void {
        $this->authService->logout();
    }
}