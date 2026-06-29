<?php
require_once __DIR__ . '/../data/Database.php';

class Reporte {
    private PDO $db;

    public function __construct() {
        $this->db = (new Database())->conectar();
    }

    public function crear(int $id_usuario, string $titulo, string $descripcion, string $ubicacion, int $id_categoria): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO Reportes (titulo, descripcion, ubicacion, id_usuario, id_categoria, id_estado)
             VALUES (?, ?, ?, ?, ?, 1)"
        );
        return $stmt->execute([$titulo, $descripcion, $ubicacion, $id_usuario, $id_categoria]);
    }

    public function listarPorUsuario(int $id_usuario): array {
        $stmt = $this->db->prepare(
            "SELECT r.id_reporte, r.titulo, r.descripcion, r.ubicacion, r.fecha_reporte,
                    c.nombre_categoria, e.nombre_estado
             FROM Reportes r
             JOIN Categorias c ON r.id_categoria = c.id_categoria
             JOIN Estados e ON r.id_estado = e.id_estado
             WHERE r.id_usuario = ?
             ORDER BY r.fecha_reporte DESC"
        );
        $stmt->execute([$id_usuario]);
        return $stmt->fetchAll();
    }

    public function listarTodos(): array {
        $stmt = $this->db->prepare(
            "SELECT r.id_reporte, r.titulo, r.descripcion, r.ubicacion, r.fecha_reporte,
                    u.nombre AS nombre_usuario, c.nombre_categoria, e.nombre_estado
             FROM Reportes r
             JOIN Usuarios u ON r.id_usuario = u.id_usuario
             JOIN Categorias c ON r.id_categoria = c.id_categoria
             JOIN Estados e ON r.id_estado = e.id_estado
             ORDER BY r.fecha_reporte DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function obtenerCategorias(): array {
        $stmt = $this->db->prepare("SELECT * FROM categorias ORDER BY nombre_categoria");
        $stmt->execute();
        return $stmt->fetchAll();
    }
}