<?php
require_once __DIR__ . '/../services/ReporteService.php';
require_once __DIR__ . '/../models/Reporte.php';

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

        $error      = '';
        $categorias = $this->reporteService->obtenerCategorias();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titulo       = trim($_POST['titulo']       ?? '');
            $descripcion  = trim($_POST['descripcion']  ?? '');
            $ubicacion    = trim($_POST['ubicacion']    ?? '');
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
                    // Guardar imagen si se subió
                    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                        $carpeta    = __DIR__ . '/../assets/img/reportes/';
                        $extension  = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                        $nombreFile = 'reporte_' . time() . '.' . $extension;
                        $ruta       = $carpeta . $nombreFile;

                        $tiposPermitidos = ['jpg', 'jpeg', 'png', 'webp'];
                        if (in_array(strtolower($extension), $tiposPermitidos)) {
                            if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta)) {
                                // Obtener el último reporte insertado
                                $reportes   = $this->reporteService->listarPorUsuario($_SESSION['id_usuario']);
                                $id_reporte = $reportes[0]['id_reporte'];

                                $reporteModel = new Reporte();
                                $reporteModel->guardarImagen($id_reporte, 'assets/img/reportes/' . $nombreFile);
                            }
                        }
                    }

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