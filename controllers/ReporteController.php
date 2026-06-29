<?php
require_once __DIR__ . '/../services/ReporteService.php';

class ReporteController {
    private ReporteService $reporteService;

    public function __construct() {
        $this->reporteService = new ReporteService();
    }

    public function nuevo(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /communityfix/?action=login');
            exit();
        }

        $error = '';
        $categorias = $this->reporteService->obtenerCategorias();

    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo      = trim($_POST['titulo']       ?? '');
            $descripcion = trim($_POST['descripcion']  ?? '');
            $ubicacion   = trim($_POST['ubicacion']    ?? '');
            $id_categoria = (int)($_POST['id_categoria'] ?? 0);

            if (empty($titulo) || empty($descripcion) || empty($ubicacion) || $id_categoria === 0) {
                $error = 'Por favor completa todos los campos.';
            } else {
                $resultado = $this->reporteService->crear(
                    $_SESSION['id_usuario'],
                    $titulo,
                    $descripcion,
                    $ubicacion,
                    $id_categoria
                );
                if ($resultado) {
                    header('Location: /communityfix/?action=reportes&exito=1');
                    exit();
                } else {
                    $error = 'Error al crear el reporte. Intenta de nuevo.';
                }
            }
        }

        include __DIR__ . '/../views/nuevo-reporte.php';
    }

    public function listar(): void {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['id_usuario'])) {
            header('Location: /communityfix/?action=login');
            exit();
        }

        $reportes = $this->reporteService->listarPorUsuario($_SESSION['id_usuario']);
        include __DIR__ . '/../views/reportes.php';
    }
}