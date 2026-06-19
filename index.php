<?php
session_start();
require_once 'controllers/AuthController.php';

$action = $_GET['action'] ?? 'login';
$controller = new AuthController();

switch ($action) {
    case 'login':
        $controller->login();
        break;
    case 'registro':
        $controller->registro();
        break;
    case 'logout':
        $controller->logout();
        break;
    default:
        $controller->login();
        break;
}
?>