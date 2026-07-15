<?php
require_once __DIR__ . '/../models/Reporte.php';
require_once __DIR__ . '/../models/Notificacion.php';

class AdminController {
    private Reporte $reporteModel;
    private Notificacion $notificacionModel;

    public function __construct() {
        $this->reporteModel      = new Reporte();
        $this->notificacionModel = new Notificacion();
    }

    private function verificarAdmin(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_usuario']) || $_SESSION['id_rol'] != 1) {
            header('Location: /communityfix/?action=login');
            exit();
        }
    }

    public function panel(): void {
        $this->verificarAdmin();
        $reportes = $this->reporteModel->listarTodos();
        $estados  = $this->reporteModel->obtenerEstados();

        $total     = count($reportes);
        $pendiente = count(array_filter($reportes, fn($r) => $r['nombre_estado'] === 'pendiente'));
        $proceso   = count(array_filter($reportes, fn($r) => $r['nombre_estado'] === 'en proceso'));
        $resuelto  = count(array_filter($reportes, fn($r) => $r['nombre_estado'] === 'resuelto'));

        include __DIR__ . '/../views/admin.php';
    }

    public function cambiarEstado(): void {
        $this->verificarAdmin();

        $id_reporte = (int)($_POST['id_reporte'] ?? 0);
        $id_estado  = (int)($_POST['id_estado']  ?? 0);

        if ($id_reporte && $id_estado) {
            $reportes = $this->reporteModel->listarTodos();
            $reporte  = array_values(array_filter($reportes, fn($r) => $r['id_reporte'] === $id_reporte))[0] ?? null;

            $estados = $this->reporteModel->obtenerEstados();
            $estado  = array_values(array_filter($estados, fn($e) => $e['id_estado'] === $id_estado))[0] ?? null;

            if ($reporte && $estado) {
                $this->reporteModel->cambiarEstado($id_reporte, $id_estado);

                $mensaje = "Tu reporte '{$reporte['titulo']}' cambió de estado a: {$estado['nombre_estado']}.";
                $this->notificacionModel->crear($reporte['id_usuario'], $mensaje, $id_reporte);
            }
        }

        header('Location: /communityfix/?action=admin');
        exit();
    }

    public function eliminar(): void {
        $this->verificarAdmin();

        $id_reporte = (int)($_POST['id_reporte'] ?? 0);
        if ($id_reporte) {
            $this->reporteModel->eliminar($id_reporte);
        }

        header('Location: /communityfix/?action=admin');
        exit();
    }
}
?>