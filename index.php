<?php
if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/ReporteController.php';
require_once __DIR__ . '/controllers/AdminController.php';

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

    case 'admin':
        (new AdminController())->panel();
        break;

    case 'admin-cambiar-estado':
        (new AdminController())->cambiarEstado();
        break;

    case 'admin-eliminar':
        (new AdminController())->eliminar();
        break;

    case 'marcar-leidas':
        require_once __DIR__ . '/models/Notificacion.php';
        $notif = new Notificacion();
        $notif->marcarTodasLeidas($_SESSION['id_usuario']);
        header('Location: /communityfix/views/dashboard.php');
        exit();
        break;

    case 'comentar':
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /communityfix/?action=login');
            exit();
        }
        require_once __DIR__ . '/models/Comentario.php';
        require_once __DIR__ . '/models/Notificacion.php';
        require_once __DIR__ . '/models/Reporte.php';

        $comentarioModel = new Comentario();
        $id_reporte      = (int)($_POST['id_reporte'] ?? 0);
        $comentario      = trim($_POST['comentario']  ?? '');

        if ($id_reporte && !empty($comentario)) {
            $comentarioModel->crear($id_reporte, $_SESSION['id_usuario'], $comentario);

            // Si es admin notifica al ciudadano
            if ($_SESSION['id_rol'] == 1) {
                $reporteModel = new Reporte();
                $reportes     = $reporteModel->listarTodos();
                $reporte      = array_values(array_filter($reportes, fn($r) => $r['id_reporte'] === $id_reporte))[0] ?? null;
                if ($reporte) {
                    $notif = new Notificacion();
                    $notif->crear(
                        $reporte['id_usuario'],
                        "El administrador comentó en tu reporte '{$reporte['titulo']}'.",
                        $id_reporte
                    );
                }
            }
        }

        if ($_SESSION['id_rol'] == 1) {
            header('Location: /communityfix/?action=admin');
        } else {
            header('Location: /communityfix/?action=reportes');
        }
        exit();
        break;

    case 'login':
    default:
        (new AuthController())->login();
        break;
}