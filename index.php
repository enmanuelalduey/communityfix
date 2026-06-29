<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ReporteController.php';

$action = $_GET['action'] ?? 'login';

switch ($action) {
    case 'registro':
        (new AuthController())->registro();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'nuevo-reporte':
        (new ReporteController())->nuevo();
        break;
    case 'reportes':
        (new ReporteController())->listar();
        break;
    case 'login':
    default:
        (new AuthController())->login();
        break;
}