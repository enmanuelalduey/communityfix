<?php
require_once __DIR__ . '/../models/Reporte.php';

class ReporteService {
    private Reporte $reporteModel;

    public function __construct() {
        $this->reporteModel = new Reporte();
    }

    public function crear(int $id_usuario, string $titulo, string $descripcion, string $ubicacion, int $id_categoria): bool {
        if (empty($titulo) || empty($descripcion) || empty($ubicacion) || $id_categoria === 0) {
            return false;
        }
        return $this->reporteModel->crear($id_usuario, $titulo, $descripcion, $ubicacion, $id_categoria);
    }

    public function listarPorUsuario(int $id_usuario): array {
        return $this->reporteModel->listarPorUsuario($id_usuario);
    }

    public function listarTodos(): array {
        return $this->reporteModel->listarTodos();
    }

    public function obtenerCategorias(): array {
        return $this->reporteModel->obtenerCategorias();
    }
}